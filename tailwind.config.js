import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Golos Text"', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 2px 8px -2px rgb(15 23 42 / 0.08)',
                raised: '0 4px 6px -1px rgb(15 23 42 / 0.08), 0 8px 20px -6px rgb(15 23 42 / 0.12)',
                glow: '0 0 40px -10px rgba(37, 99, 235, 0.25)',
                'glow-lg': '0 0 60px -15px rgba(37, 99, 235, 0.35)',
                glass: '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                'glass-raised': '0 16px 48px 0 rgba(15, 23, 42, 0.14)',
            },
        },
    },

    plugins: [forms, typography],
};
