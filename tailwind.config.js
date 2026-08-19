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
                // Karla, because that is what the stylesheets ask for 163 times
                // over. Figtree was here and was loaded on every page from a
                // second font host, but nothing in the design ever referenced
                // it — the Play CDN ignored this config entirely, so font-sans
                // silently resolved to the system stack.
                sans: ['Karla', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'teal': {
                    '50': '#f0fbfc',
                    '100': '#e0f7f8',
                    '200': '#b3eff2',
                    '300': '#80e7eb',
                    '400': '#4ddfe4',
                    '500': '#038b89',
                    '600': '#027a78',
                    '700': '#026a68',
                    '800': '#015a58',
                    '900': '#055860',
                },
                'gold': '#ee9d1d',
                'dark-gray': '#404952',
                'secondary-gray': '#57616a',
                'light': '#F5F5F5',
            },
            borderRadius: {
                'xl': '20px',
            },
            boxShadow: {
                'custom': '0 25px 50px rgba(0, 0, 0, 0.08)',
                'custom-hover': '0 40px 80px rgba(0, 0, 0, 0.12)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'gradient-shift': 'gradientShift 4s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                gradientShift: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
            },
        },
    },

    plugins: [forms],
};
