import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['"Golos Text"', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 2px 8px -2px rgb(15 23 42 / 0.08)',
                raised: '0 4px 6px -1px rgb(15 23 42 / 0.08), 0 8px 20px -6px rgb(15 23 42 / 0.12)',
            },
        },
    },

    plugins: [forms, typography],
};
