// vite.config.mjs
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'public/build',   // safe separate folder
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: 'src/View/assets/css/app.css',
            output: {
                entryFileNames: '[name]-[hash].css',
            },
        },
    },

});
