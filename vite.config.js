// vite.config.js
const path = require('path')
const { defineConfig } = require('vite')
import vue from '@vitejs/plugin-vue'
import { viteExternalsPlugin } from 'vite-plugin-externals'

// InvoiceShelf 2.3.0 and later style the app with Tailwind 4, which keeps its
// rules in cascade layers, and CSS outside any layer beats every layered rule.
// Left unlayered, this module's utilities (.hidden, .flex, ...) overrode the
// app's responsive ones and hid the sidebar. The built stylesheet goes into the
// app's `utilities` layer instead, declared in the app's order first, so the
// app's own rule wins wherever both define a class. Hosts before 2.3.0 have no
// layers, and their rules keep winning there too.
const inHostUtilitiesLayer = () => ({
  name: 'in-host-utilities-layer',
  closeBundle() {
    const fs = require('fs')
    const file = path.resolve(__dirname, 'dist/style.css')

    if (!fs.existsSync(file)) {
      return
    }

    const css = fs.readFileSync(file, 'utf8').replace(/^@charset "[^"]*";\s*/, '')
    fs.writeFileSync(file, `@layer theme, base, components, utilities;\n@layer utilities {\n${css}\n}\n`)
  },
})

module.exports = defineConfig({
  build: {
    lib: {
      entry: path.resolve(__dirname, 'Resources/scripts/module.js'),
      name: 'MyLib',
      fileName: (format) => `whitelabel.${format}.js`,
    },
    outDir: './dist',
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, '../../resources'),
      '~': path.resolve(__dirname, 'Resources'),
    },
  },
  plugins: [
    vue(),
    viteExternalsPlugin({
      vue: 'Vue',
    }),
    inHostUtilitiesLayer(),
  ],
})
