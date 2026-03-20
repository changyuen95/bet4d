import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['Rubik', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                green: {
                    primary: '#028a36',
                    light: '#03a843',
                    dark: '#016828',
                },
            },
        },
    },

    plugins: [forms],
};
