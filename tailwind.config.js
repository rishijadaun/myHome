import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/**/*.php",
    ],
    safelist: [
        'hidden',
        'block',
        'flex',
        'grid',
        'inline',
        'inline-block',
        'inline-flex',
        'sm:hidden',
        'sm:block',
        'sm:flex',
        'sm:grid',
        'md:hidden',
        'md:block',
        'md:flex',
        'md:grid',
        'lg:hidden',
        'lg:block',
        'lg:flex',
        'lg:grid',
        'xl:hidden',
        'xl:block',
        'xl:flex',
        'xl:grid',
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
            maxWidth: {
                '7xl': '100rem',
            },
            boxShadow: {
                sheet: '0 -4px 24px rgba(0, 0, 0, 0.12)',
            },
        },
    },
    plugins: [],
};
