import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

const laravelRoot = path.resolve(__dirname, '../../../../../..')

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname),
    },
  },
  build: {
    outDir: path.resolve(laravelRoot, 'public/build/pos'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'main.ts'),
    },
  },
  server: {
    port: 5176,
    origin: 'http://localhost:5176',
  },
})
