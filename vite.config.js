import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Polices téléchargées au moment du build et servies par notre serveur (pas d'appel à un service tiers).
            fonts: [
                bunny('Lato', { weights: [400, 700, 900], optimizedFallbacks: false }),
                bunny('Raleway', { weights: [600, 700, 800], optimizedFallbacks: false }),
                bunny('Caveat', { weights: [600], optimizedFallbacks: false }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        hmr: { host: 'localhost' },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
