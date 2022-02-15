import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import alertStore from './store/alert';
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import Vuex from 'vuex';

import axios_instance from './service';

store.dispatch('setInfo');
window.axios = axios_instance;

loadFonts()
createApp(App)
  .use(Vuex)
  .use(router)
  .use(store)
  .use(alertStore)
  .use(vuetify)
  .mount('#app')
