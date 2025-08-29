import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'sent',
        'received',
        'justify-end',
        'justify-start',
        // অন্য কোনো ডাইনামিক ক্লাস থাকলে সেগুলোও যোগ করুন
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
