import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  define: {
    'process.env.NODE_ENV': '"production"',
  },
  build: {
    lib: {
      entry: path.resolve(__dirname, 'Resources/scripts/module.js'),
      name: 'WhiteLabel',
      fileName: (format) => `whitelabel.${format}.js`,
      cssFileName: 'style',
    },
    outDir: './dist',
    rolldownOptions: {
      external: ['vue'],
      output: {
        globals: {
          vue: 'Vue',
        },
      },
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, '../../resources'),
      '~': path.resolve(__dirname, 'Resources'),
    },
  },
  plugins: [
    tailwindcss(),
    vue(),
  ],
  css: {
    minify: false,
  },
})