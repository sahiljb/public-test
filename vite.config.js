import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/scss/light/plugins/fullcalendar/custom-fullcalendar.scss',
                'resources/scss/light/assets/components/modal.scss',
                'resources/scss/light/assets/main.scss',
                'resources/scss/layouts/siteadmin/light/structure.scss',
                'resources/scss/light/assets/elements/popover.scss',
                'resources/scss/light/assets/elements/alert.scss',
                'resources/scss/light/plugins/tomSelect/custom-tomSelect.scss',
                'resources/assets/js/elements/popovers.js',
                'resources/scss/light/plugins/table/datatable/dt-global_style.scss',
                'resources/scss/light/assets/apps/invoice-list.scss',
                'resources/scss/light/assets/pages/error/style-maintanence.scss',
                'resources/scss/layouts/siteadmin/light/loader.scss',
                'resources/layouts/siteadmin/loader.js',
                'resources/layouts/siteadmin/app.js',
                'resources/scss/light/assets/authentication/auth-boxed.scss',
                'resources/scss/light/assets/components/list-group.scss',
                'resources/scss/light/assets/widgets/modules-widgets.scss',
                'resources/scss/light/plugins/perfect-scrollbar/perfect-scrollbar.scss',
                'resources/assets/js/widgets/_wSix.js',
            ],
            refresh: true,
        }),
    ],
});
