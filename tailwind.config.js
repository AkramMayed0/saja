import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Unified font: IBM Plex Sans Arabic for all headings, body, and UI elements
                sans: ['"IBM Plex Sans Arabic"', ...defaultTheme.fontFamily.sans],
                display: ['"IBM Plex Sans Arabic"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand teal sampled from the Saja logo (#00909B = 600)
                primary: {
                    50: '#effcfc',
                    100: '#d5f5f5',
                    200: '#afeaec',
                    300: '#78d9dd',
                    400: '#3abfc6',
                    500: '#14a3ab',
                    600: '#00909b',
                    700: '#06707a',
                    800: '#0c5a63',
                    900: '#0f4b53',
                    950: '#032f36',
                },
                // Brand leaf green sampled from the logo (#35B047 = 500)
                leaf: {
                    50: '#f0fbf1',
                    100: '#dcf6df',
                    200: '#bbebc1',
                    300: '#86da92',
                    400: '#4fc260',
                    500: '#35b047',
                    600: '#258d36',
                    700: '#20702d',
                    800: '#1e5928',
                    900: '#1a4a23',
                    950: '#08280f',
                },
                // Neutrals follow the slate tone of the logo wordmark
                gray: colors.slate,
            },
            borderRadius: {
                '4xl': '2rem',
            },
            boxShadow: {
                soft: '0 1px 2px rgb(15 23 42 / 0.04), 0 4px 16px -4px rgb(15 23 42 / 0.08)',
                lift: '0 2px 4px rgb(15 23 42 / 0.04), 0 18px 40px -12px rgb(15 23 42 / 0.18)',
                book: '0 1px 1px rgb(15 23 42 / 0.08), 0 10px 24px -8px rgb(15 23 42 / 0.35)',
                glow: '0 10px 30px -10px rgb(0 144 155 / 0.55)',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                blob: {
                    '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                    '33%': { transform: 'translate(30px, -40px) scale(1.08)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.95)' },
                },
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
                'fade-up': {
                    from: { opacity: '0', transform: 'translateY(16px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                float: 'float 6s ease-in-out infinite',
                'float-slow': 'float 9s ease-in-out infinite',
                blob: 'blob 18s ease-in-out infinite',
                marquee: 'marquee 40s linear infinite',
                'fade-up': 'fade-up .7s cubic-bezier(.2,.7,.2,1) both',
            },
        },
    },

    plugins: [forms],
};
