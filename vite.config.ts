import { resolve } from 'path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: ['resources/client/tailwind.css', 'resources/client/app.ts'],
      refresh: true,
    }),
  ],
  resolve: {
    tsconfigPaths: true,
    alias: {
      '@app': resolve(__dirname, 'resources/client'),
      '@vendor': resolve(__dirname, 'vendor/'),
    },
  },
  server: {
    watch: {
      ignored: ['**/storage/framework/views/**'],
    },
  },
});
