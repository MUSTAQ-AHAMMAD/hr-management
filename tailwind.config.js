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
            },
            colors: {
                'primary': {
                    50: 'var(--color-primary-50)',
                    100: 'var(--color-primary-100)',
                    200: 'var(--color-primary-200)',
                    300: 'var(--color-primary-300)',
                    400: 'var(--color-primary-400)',
                    500: 'var(--color-primary-500)',
                    600: 'var(--color-primary-600)',
                    700: 'var(--color-primary-700)',
                    800: 'var(--color-primary-800)',
                    900: 'var(--color-primary-900)',
                    950: 'var(--color-primary-950)',
                },
                'navy': {
                    50: 'var(--color-navy-50)',
                    100: 'var(--color-navy-100)',
                    200: 'var(--color-navy-200)',
                    300: 'var(--color-navy-300)',
                    400: 'var(--color-navy-400)',
                    500: 'var(--color-navy-500)',
                    600: 'var(--color-navy-600)',
                    700: 'var(--color-navy-700)',
                    800: 'var(--color-navy-800)',
                    900: 'var(--color-navy-900)',
                    950: 'var(--color-navy-950)',
                },
                'cobalt': {
                    50: 'var(--color-cobalt-50)',
                    100: 'var(--color-cobalt-100)',
                    200: 'var(--color-cobalt-200)',
                    300: 'var(--color-cobalt-300)',
                    400: 'var(--color-cobalt-400)',
                    500: 'var(--color-cobalt-500)',
                    600: 'var(--color-cobalt-600)',
                    700: 'var(--color-cobalt-700)',
                    800: 'var(--color-cobalt-800)',
                    900: 'var(--color-cobalt-900)',
                    950: 'var(--color-cobalt-950)',
                },
            },
        },
    },

    plugins: [forms],
};
