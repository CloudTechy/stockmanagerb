import { defineConfig } from "vite";
import { createVuePlugin } from "vite-plugin-vue2";
import path from "path";

export default defineConfig({
    plugins: [createVuePlugin()],

    server: {
        port: 3000,
        proxy: {
            "/api": {
                target: "http://localhost:8000/api",
                changeOrigin: true,
                secure: false,
            },
        },
    },

    // define: {
    //     $: "jquery",
    //     jQuery: "jquery",
    // },
    optimizeDeps: {
        include: [
            "jquery",
            "vue-loading-overlay",
            "vue-progressbar",
            "vue-toastr",
            "vue-sweetalert2",
            "vue-session",
            "vue-cookies",
        ],
        exclude: ['uglify-js','laravel-mix' ,'terser-webpack-plugin', '@swc/core', 'esbuild'],
    },

    build: {
        outDir: "dist",
        rollupOptions: {
            external: ['uglify-js', 'terser-webpack-plugin', 'laravel-mix', '@swc/core', 'esbuild'],
          },
    },

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./src"),
            jquery: "jquery/dist/jquery.js",
        },
    },
});
