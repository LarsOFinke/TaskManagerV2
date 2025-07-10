import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import vue from "@vitejs/plugin-vue";
export default defineConfig({
    plugins: [
        symfonyPlugin(),
        vue({
            template: {
                transformAssetUrls: { base: null, includeAbsolute: false },
            },
        }),
    ],
    resolve: { alias: { "@": "/assets/js" } },
    server: { port: 5173, strictPort: true },
    build: {
        manifest: true,
        outDir: "public/build",
        rollupOptions: { input: { app: "assets/js/app.js" } },
    },
});
