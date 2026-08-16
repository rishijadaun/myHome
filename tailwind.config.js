import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#4bb59d',
                    light: '#e6f7f3',
                    dark: '#3a9a85',
                    50: '#f0fdf9',
                    100: '#ccf0e8',
                },
                primary: {
                    DEFAULT: '#1a1a7f',
                    light: '#eef2ff',
                    dark: '#23239c',
                },
            },
            boxShadow: {
                sheet: '0 -4px 24px rgba(0, 0, 0, 0.12)',
            },
        },
    },
    plugins: [],
};
