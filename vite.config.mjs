import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve, relative, dirname } from 'path';
import { globSync } from 'glob';

// Find all JS files under src/View/*/js/**
const jsEntries = globSync('src/View/assets/js/**/*.js').reduce((entries, file) => {
    const name = file
        .replace(/^src\/View\//, '')   // remove src/View/
        .replace(/\.js$/, '');         // remove extension
    entries[name] = resolve(file);
    return entries;
}, {});

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                app: 'src/View/assets/css/app.css', // CSS entry
                ...jsEntries,                        // all JS files dynamically
            },
            output: {
                entryFileNames: (chunk) => {
                    if (chunk.facadeModuleId) {
                        // compute relative path from src/View
                        const relPath = relative(resolve('src/View'), chunk.facadeModuleId);
                        const pathParts = relPath.split(/[/\\]/); // cross-platform
                        // replace 'js' folder root with 'js/view/...'
                        const jsIndex = pathParts.indexOf('js');
                        if (jsIndex !== -1) {
                            const subPath = pathParts.slice(jsIndex + 1).join('/');
                            return `js/${subPath.replace(/\.js$/, '')}.js`;
                        }
                    }
                    return '[name]-[hash].js';
                },
                assetFileNames: '[name]-[hash][extname]',
            },
        },
    },
});
