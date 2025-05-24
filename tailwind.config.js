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
                hitam: '#464646',
                error: '#882614',
                info: '#064974',
                success: '#176608',
                warning: '#7C370B',
                merah: {
                    100: '#FFE4DF',
                    500: '#FF5132',
                    600: '#ED3615',
                },
                biru: {
                    100: '#DEF1FF',
                    600: '#0083D4',
                    700: '#0068AB',
                },
                hijau: {
                    100: '#EEFCD4',
                    200: '#D8FAAA',
                    300: '#BAF27D',
                    400: '#9BE65B',
                    500: '#6FD62A',
                    600: '#53B81E',
                    700: '#3A9A15',
                    800: '#267C0D',
                    900: '#176608',
                },
                kuning: {
                    100: '#FFF5C5',
                    500: '#FFA600',
                    600: '#E27D00',
                },
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
            boxShadow: {
                abu: '0px 0px 10px rgba(101, 101, 101, 0.4)', // <- ini custom shadow kamu
            },
        },
    },

    plugins: [forms, daisyui],
};