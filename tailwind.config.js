import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    safelist: [
    // ← Enumで返している色クラスを全部列挙
    'bg-blue-100','text-blue-800','ring-blue-200',
    'bg-indigo-100','text-indigo-800','ring-indigo-200',
    'bg-yellow-100','text-yellow-800','ring-yellow-200',
    'bg-rose-100','text-rose-800','ring-rose-200',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
