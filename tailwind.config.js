import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [require('./vendor/wireui/wireui/tailwind.config.js')],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './wireui/wireui/src/*.php',
        './wireui/wireui/ts/**/*.ts',
        './wireui/wireui/src/WireUi/**/*.php',
        './wireui/wireui/src/Components/**/*.php'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans]
            }
        }
    },
    plugins: []
};
