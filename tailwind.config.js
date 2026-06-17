import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // Configuration for Tailwind CSS

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
                ikl: {
                    50: '#FDF4F3',
                    100: '#FCE7E5',
                    200: '#F7C5C1',
                    500: '#E04132',
                    600: '#C0281A',
                    700: '#A0211A',
                    800: '#7A1913',
                    900: '#4D100C',
                }
            }
        },
    },

    plugins: [forms],
};
