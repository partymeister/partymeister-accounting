import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

const laravelRoot = path.resolve(__dirname, '../../../../../..')

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname),
      '@auth': path.resolve(laravelRoot, 'packages/partymeister-apps/shared/auth'),
    },
  },
  envDir: path.resolve(laravelRoot, 'packages/partymeister-apps'),
  base: '/build/pos/',
  build: {
    outDir: path.resolve(laravelRoot, 'public/build/pos'),
    emptyOutDir: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'index.html'),
    },
  },
  server: {
    port: 5176,
    origin: 'http://localhost:5176',
  },
})
