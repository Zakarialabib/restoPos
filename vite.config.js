import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import autoprefixer from 'autoprefixer';
import tailwindcss from 'tailwindcss';
import postcssImport from 'postcss-import';
import postcssNesting from 'postcss-nesting';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/css/app.css',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'resources/js/**/*.js',
                'app/Livewire/**/*.php',
                'app/Http/Controllers/**/*.php',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
            '~': resolve(__dirname, 'node_modules'),
        },
    },
    css: {
        postcss: {
            plugins: [
                postcssImport(),
                postcssNesting(),
                tailwindcss(),
                autoprefixer(),
            ],
        },
        devSourcemap: true,
    },
    build: {
        sourcemap: process.env.NODE_ENV === 'development',
        chunkSizeWarningLimit: 1000,
    },
});
