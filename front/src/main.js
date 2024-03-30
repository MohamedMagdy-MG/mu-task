import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { createI18n } from "vue-i18n";
import EN from "./locales/en.json";
import AR from "./locales/ar.json";
import "./index.css";
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import 'primeicons/primeicons.css';





const i18n = createI18n({
  locale: localStorage.getItem('language'),
  messages: { en: EN, ar: AR },
});
createApp(App).use(ToastPlugin).use(i18n).use(store).use(router).mount("#app");
