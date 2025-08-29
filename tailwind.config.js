import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    // এই অংশটুকু যোগ করুন
    safelist: [
        'justify-end',
        'justify-start',
        'bg-indigo-600',
        'text-white',
        'bg-gray-200',
        'dark:bg-gray-700',
        'text-gray-800',
        'dark:text-gray-100',
        'ml-2',
        'mr-2'
    ],
    darkMode: 'class', // ⬅️ এটা অবশ্যই থাকতে হবে
    theme: {
        extend: {
            fontFamily: {
                sans: ['Shurjo', 'Roboto', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
