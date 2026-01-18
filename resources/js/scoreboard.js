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
            const titleEl = document.getElementById('sb-title');
            const drawEl = document.getElementById('sb-draw');
            const dateEl = document.getElementById('sb-date');
            const firstEl = document.getElementById('sb-first');
            const secondEl = document.getElementById('sb-second');
            const thirdEl = document.getElementById('sb-third');
            const jackpotEl = document.getElementById('sb-jackpot');
            const jackpot2El = document.getElementById('sb-jackpot2');
            const specialUl = document.getElementById('sb-special');
            const consolationUl = document.getElementById('sb-consolation');

            if (!titleEl || !drawEl || !dateEl || !firstEl || !secondEl || !thirdEl || !specialUl || !consolationUl) {
                console.error('Missing scoreboard elements in DOM');
                return false;
            }

            titleEl.textContent = data.title ?? titleEl.textContent;
            drawEl.textContent = data.draw_no ?? drawEl.textContent;
            dateEl.textContent = data.date ?? dateEl.textContent;
            firstEl.textContent = data.first ?? firstEl.textContent;
            secondEl.textContent = data.second ?? secondEl.textContent;
            thirdEl.textContent = data.third ?? thirdEl.textContent;
            if (jackpotEl && (data.jackpot1 || data.jackpot)) {
                const jackpotValue = data.jackpot1 || data.jackpot;
                const formattedValue = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(jackpotValue);
                jackpotEl.textContent = 'RM ' + formattedValue;
            }
            if (jackpot2El && data.jackpot2) {
                const formattedValue2 = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data.jackpot2);
                jackpot2El.textContent = 'RM ' + formattedValue2;
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

            if (Array.isArray(data.special)) {
                specialUl.innerHTML = '';
                data.special.forEach(num => {
                    const li = document.createElement('li');
                    li.textContent = num;
                    specialUl.appendChild(li);
                });
            }

            if (Array.isArray(data.consolation)) {
                consolationUl.innerHTML = '';
                data.consolation.forEach(num => {
                    const li = document.createElement('li');
                    li.textContent = num;
                    consolationUl.appendChild(li);
                });
            }

            return true;
        } catch (e) {
            console.error('Failed applying scoreboard data', e);
            return false;
        }
    }

    function ensureStatusBanner() {
        let banner = document.getElementById('sb-status');
        if (!banner) {
            banner = document.createElement('div');
            banner.id = 'sb-status';
            banner.style.position = 'fixed';
            banner.style.bottom = '1vh';
            banner.style.left = '1vw';
            banner.style.padding = '0.6vh 1vw';
            banner.style.background = 'rgba(0,0,0,0.5)';
            banner.style.color = '#fff';
            banner.style.fontSize = '1.2vw';
            banner.style.borderRadius = '0.6vw';
            banner.style.zIndex = '9999';
            document.body.appendChild(banner);
        }
        return banner;
    }

    function setStatus(msg) {
        const banner = ensureStatusBanner();
        banner.textContent = msg;
    }

    setStatus('Connecting to scoreboard channel...');
    const channel = window.Echo.channel('scoreboard');
    channel.listen('.ScoreboardUpdated', (event) => {
        setStatus('Update received');
        const data = event?.payload?.stc4d ?? null;
        if (!data) {
            console.error('No data in event payload', event);
            setStatus('Update received (no data)');
            return;
        }
        const ok = applyScoreboard(data);
        setStatus(ok ? 'Display updated' : 'Display update failed');
    });

    if (debug) {
        setStatus('Debug mode: using sample data');
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
