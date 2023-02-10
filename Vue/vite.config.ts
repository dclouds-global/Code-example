import { fileURLToPath, URL } from 'node:url'
import EslintPlugin from 'vite-plugin-eslint'
import StyleLintPlugin from 'vite-plugin-stylelint'
import dns from 'dns'

dns.setDefaultResultOrder('verbatim')

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

import { createSvgIconsPlugin } from 'vite-plugin-svg-icons'
import path from 'path'
import VitePluginFonts from 'vite-plugin-fonts'

const styleLintConfig = StyleLintPlugin({
  files: ['src/**/*.{vue,scss}'],
  fix: true,
})

const eslintConfig = EslintPlugin({
  fix: true,
  cache: false,
})

const fontsConfig = VitePluginFonts({
  custom: {
    families: [
      {
        name: 'Rubik',
        local: 'Rubik',
        src: './src/assets/fonts/*.ttf',
      },
    ],
  },
})

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '') as ImportMetaEnv

  return {
    build: {
      chunkSizeWarningLimit: 3000,
    },
    plugins: [
      vue(),
      styleLintConfig,
      eslintConfig,
      fontsConfig,
      createSvgIconsPlugin({
        iconDirs: [path.resolve(process.cwd(), 'src/assets/icons')],
        symbolId: 'icon-[dir]-[name]',
        inject: 'body-first',
        customDomId: '__svg__icons__dom__',
      }),
    ],
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: '@use "@/styles/resources" as *;',
        },
      },
    },
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url)),
      },
    },
    server: {
      proxy: {
        '/albums': {
          target: env.VITE_BASE_URL, // example work for https://jsonplaceholder.typicode.com
          changeOrigin: true,
          secure: false,
          cookieDomainRewrite: {
            '.dclouds.ru': 'localhost',
          },
        },
      },
    },
  }
})
