import { resolve } from 'path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwind from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import tsconfigPaths from 'vite-tsconfig-paths';

export default defineConfig({
  css: {
    postcss: {
      plugins: [tailwind(), autoprefixer()],
    },
  },
  plugins: [
    tsconfigPaths(),
    laravel({
      input: ['resources/client/app.css', 'resources/client/app.ts'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@app': resolve(__dirname, 'resources/client'),
      '@vendor': resolve(__dirname, 'vendor/'),
    },
  },
});
