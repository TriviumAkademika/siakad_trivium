import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';
import { utilities } from 'daisyui/imports';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                putih: '#FDFDFD',
                info: '#064974',
                hitam: '#464646',

                brand: {
                    50: '#EEF6FF',
                    100: '#DAEBFF',
                    200: '#BDDCFF',
                    300: '#90C7FF',
                    400: '#51A2FF',
                    500: '#3584FC',
                    600: '#1F65F1',
                    700: '#174EDE',
                    800: '#1941B4',
                    900: '#1A3A8E',
                    950: '#152556',
                },
            },
        },
    },

    plugins: [forms, daisyui],
};