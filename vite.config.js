import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [laravel(['resources/js/app.js']), vue()],
    server: {
        host: true,
    },
    css: {
        preprocessorOptions:
            {scss:
                    {additionalData: `@import "bootstrap/dist/css/bootstrap.min.css";`}
            }
    }

});
