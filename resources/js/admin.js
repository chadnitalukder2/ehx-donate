import { createApp } from 'vue';
import App from '../vue/App.vue';
import 'element-plus/dist/index.css'
import router from '../vue/routes.js';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'


window.$ = window.jQuery = jQuery;

function $t (string) {
    return window?.EHXDonate?.i18n[string] || string;
}

// ad active class to the current menu item


// Create and mount Vue app
const app = createApp(App);
app.use(ElementPlus);
app.config.globalProperties.$t = $t;
app.use(router);
app.mount('#ehx-donate-admin')
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
  }