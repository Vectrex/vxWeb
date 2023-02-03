<<<<<<< HEAD
=======
// vite.config.js
import { resolve } from 'path'
>>>>>>> vite
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  build: {
<<<<<<< HEAD
    // generate manifest.json in outDir
    manifest: true,
    rollupOptions: {
      // overwrite default .html entry
      input: './src/vxweb.js'
    }
  }
=======
    outDir: resolve(__dirname, 'dist/vue'),
    lib: {
      // Could also be a dictionary or array of multiple entry points
      entry: resolve(__dirname, 'vue/build/vxweb.js'),
      name: 'vxweb',
      // the proper extensions will be added
      fileName: 'vxweb',
    },
    rollupOptions: {
      // make sure to externalize deps that shouldn't be bundled
      // into your library
      external: ['vue'],
      output: {
        // Provide global variables to use in the UMD build
        // for externalized deps
        globals: {
          vue: 'Vue',
        },
      },
    },
  },
>>>>>>> vite
})