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
            colors: {
                'brand-green': '#008000', // Le vert vif des boutons et du footer
                'brand-dark': '#1a1a1a',  // Pour les titres et textes denses
            },
            fontFamily: {
                'sans': ['Inter', 'ui-sans-serif', 'system-ui'], // Proche de ta maquette
            },
        },
    },

    plugins: [forms],
};
