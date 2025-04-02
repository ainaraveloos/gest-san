import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Couleurs principales
            

                // Couleurs de fond et textes
                background: '#F1F8E9',
                text: {
                  DEFAULT: '#37474F',         // Texte par défaut
                  muted: '#90A4AE',           // Texte secondaire
                  title: '#263238',           // Pour les titres
                  content: '#455A64',         // Pour le contenu courant
                },

                // Spécifique aux boutons avec variantes dynamiques
                button: {
                  primary: '#2E7D32',
                  'primary-hover': '#388E3C',
                  'primary-active': '#1B5E20',
                  secondary: '#0288D1',
                  'secondary-hover': '#03A9F4',
                  'secondary-active': '#01579B',
                  danger: '#C62828',
                  'danger-hover': '#E53935',
                  'danger-active': '#8E0000',
                },
              },
              fontSize: {
                // Tailles de police pour les titres
                'title-lg': '2rem',    // Ex : 32px
                'title-md': '1.5rem',   // Ex : 24px
                'title-sm': '1.25rem',  // Ex : 20px
                // Tailles de police pour le contenu
                'content-lg': '1rem',   // Ex : 16px
                'content-md': '0.875rem', // Ex : 14px
                'content-sm': '0.75rem',  // Ex : 12px
              },
              backgroundColor: theme => ({
                page: theme('colors.background'),
                header: theme('colors.primary'),
                footer: theme('colors.primary'),
                // Exemples pour boutons si besoin de classes dédiées
                'button-primary': theme('colors.button.primary'),
                'button-secondary': theme('colors.button.secondary'),
              }),
              borderRadius: {
                btn: '0.375rem', // Pour des boutons arrondis (6px)
              },
              spacing: {
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
              },

        },
    },

    plugins: [forms],
};
