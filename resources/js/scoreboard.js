import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    encrypted: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

console.log('Echo instance created with config:', {
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
});

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const debug = params.get('debug') === '1';

    const scaleParam = params.get('scale') || params.get('zoom');
    const scale = parseFloat(scaleParam);
    if (!Number.isNaN(scale) && scale > 0) {
        document.documentElement.style.setProperty('--ui-scale', String(scale));
        console.log('Applied UI scale:', scale);
    }

    function applyScoreboard(data) {
        try {
            const marqueeEl = document.getElementById('sb-marquee');
            const drawEl = document.getElementById('sb-draw');
            const dateEl = document.getElementById('sb-date');
            const firstEl = document.getElementById('sb-first');
            const secondEl = document.getElementById('sb-second');
            const thirdEl = document.getElementById('sb-third');
            const jackpotEl = document.getElementById('sb-jackpot');
            const jackpot2El = document.getElementById('sb-jackpot2');
            const consolationUl = document.getElementById('sb-consolation');

            if (drawEl && data.draw_no) drawEl.textContent = data.draw_no;
            if (dateEl && data.date) dateEl.textContent = data.date;
            if (firstEl && data.first) firstEl.textContent = data.first;
            if (secondEl && data.second) secondEl.textContent = data.second;
            if (thirdEl && data.third) thirdEl.textContent = data.third;
            
            // Update jackpot amounts
            if (jackpotEl && (data.jackpot1 || data.jackpot)) {
                const jackpotValue = data.jackpot1 || data.jackpot;
                const formattedValue = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jackpotValue);
                jackpotEl.textContent = 'RM ' + formattedValue;
            }
            if (jackpot2El && data.jackpot2) {
                const formattedValue2 = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data.jackpot2);
                jackpot2El.textContent = 'RM ' + formattedValue2;
            }

            // Update marquee with jackpot values
            if (marqueeEl && (data.jackpot1 || data.jackpot2)) {
                const jp1 = data.jackpot1 || data.jackpot;
                const jp2 = data.jackpot2;
                const jp1Formatted = jp1 ? new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jp1) : '-';
                const jp2Formatted = jp2 ? new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jp2) : '-';
                marqueeEl.textContent = `Welcome to STC 4D Lottery • Jackpot 1 : RM${jp1Formatted} • Jackpot 2 : RM${jp2Formatted} • Next Special draw on 10/2/2026`;
            }

            // Update jackpot combinations
            if (data.first && data.second && data.third) {
                const combo1 = document.getElementById('sb-combo-1');
                const combo2 = document.getElementById('sb-combo-2');
                const combo3 = document.getElementById('sb-combo-3');
                const combo4 = document.getElementById('sb-combo-4');
                const combo5 = document.getElementById('sb-combo-5');
                const combo6 = document.getElementById('sb-combo-6');
                
                if (combo1) combo1.textContent = data.first + ' + ' + data.second;
                if (combo2) combo2.textContent = data.first + ' + ' + data.third;
                if (combo3) combo3.textContent = data.second + ' + ' + data.third;
                if (combo4) combo4.textContent = data.second + ' + ' + data.first;
                if (combo5) combo5.textContent = data.third + ' + ' + data.first;
                if (combo6) combo6.textContent = data.third + ' + ' + data.second;
            }

            // Update special prizes (3 columns: 5-4-4 distribution)
            if (Array.isArray(data.special)) {
                // Left column (1-5)
                const specialLeft = document.getElementById('sb-special-left');
                if (specialLeft) {
                    specialLeft.innerHTML = '';
                    for (let i = 0; i < 5 && i < data.special.length; i++) {
                        const div = document.createElement('div');
                        div.className = 'number-item';
                        div.style.marginBottom = '0.3vh';
                        div.innerHTML = `<span class="number-index">${i + 1}</span><span style="font-size: 5vw; font-weight: bold;">${data.special[i] || '-'}</span>`;
                        specialLeft.appendChild(div);
                    }
                }
                
                // Middle column (6-9)
                const specialMiddle = document.getElementById('sb-special-middle');
                if (specialMiddle) {
                    specialMiddle.innerHTML = '';
                    for (let i = 5; i < 9 && i < data.special.length; i++) {
                        const div = document.createElement('div');
                        div.className = 'number-item';
                        div.style.marginBottom = '0.3vh';
                        div.innerHTML = `<span class="number-index">${i + 1}</span><span style="font-size: 5vw; font-weight: bold;">${data.special[i] || '-'}</span>`;
                        specialMiddle.appendChild(div);
                    }
                }
                
                // Right column (10-13)
                const specialRight = document.getElementById('sb-special-right');
                if (specialRight) {
                    specialRight.innerHTML = '';
                    for (let i = 9; i < 13 && i < data.special.length; i++) {
                        const div = document.createElement('div');
                        div.className = 'number-item';
                        div.style.marginBottom = '0.3vh';
                        div.innerHTML = `<span class="number-index">${i + 1}</span><span style="font-size: 5vw; font-weight: bold;">${data.special[i] || '-'}</span>`;
                        specialRight.appendChild(div);
                    }
                }
            }

            // Update consolation prizes (inherits 3.5rem font size from CSS)
            if (Array.isArray(data.consolation) && consolationUl) {
                consolationUl.innerHTML = '';
                data.consolation.forEach(num => {
                    const div = document.createElement('div');
                    div.textContent = num;
                    consolationUl.appendChild(div);
                });
            }

            return true;
        } catch (e) {
            console.error('Failed applying scoreboard data', e);
            return false;
        }
    }

    const channel = window.Echo.channel('scoreboard');
    
    channel.listen('.ScoreboardUpdated', (event) => {
        // Handle marquee-only updates
        if (event?.payload?.marquee) {
            const marqueeEl = document.getElementById('sb-marquee');
            if (marqueeEl) {
                let message = event.payload.marquee.message;
                
                // Replace placeholders with jackpot values if available
                const jackpot1 = event.payload.marquee.jackpot1 || 0;
                const jackpot2 = event.payload.marquee.jackpot2 || 0;
                const jp1Formatted = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jackpot1);
                const jp2Formatted = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jackpot2);
                
                message = message.replace(/%jackpot1%/g, `${jp1Formatted}`);
                message = message.replace(/%jackpot2%/g, `${jp2Formatted}`);
                
                marqueeEl.textContent = message;
                console.log('Marquee updated:', message);
            }
            return;
        }
        
        // Handle full scoreboard updates
        const data = event?.payload?.stc4d ?? null;
        if (!data) {
            console.error('No data in event payload', event);
            return;
        }
        applyScoreboard(data);
    });

    if (debug) {
        const sample = {
            title: 'WINNING RESULTS',
            draw_no: '001/26',
            date: '03/01/2026 (SAT)',
            first: '9058',
            second: '5706',
            third: '0124',
            special: ['0590','6087','2711','7952','7428','2318','3512','5466','9736','7233'],
            consolation: ['3881','5307','1528','7515','5826','9184','3284','8544','2167','7520'],
            jackpot1: '1,000,000',
            jackpot2: '500,000'
        };
        applyScoreboard(sample);
    }
});
