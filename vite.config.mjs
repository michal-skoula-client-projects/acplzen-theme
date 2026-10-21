import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ command }) => {
    const isBuild = command === 'build';

    return {
        publicDir: 'public',
        base: isBuild ? '/wp-content/themes/acplzen/dist/' : '/',
        server: {
            port: 3000,
            cors: true,
            origin: 'http://acplzen.local',
        },
        build: {
            manifest: true,
            outDir: 'dist',
            rollupOptions: {
                input: [
                    'resources/js/app.js',
                    'resources/css/app.css',
                ],
            },
        },
        plugins: [
            tailwindcss(),
        ],
    }
});
