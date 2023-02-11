import './bootstrap';

import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import FloatingVue from 'floating-vue';
import { createApp } from 'vue';
import VueSelect from 'vue-select';
import App from './App.vue';
import router from './router';
import { store } from './store';

library.add(fas);

createApp(App)
  .use(router)
  .use(store)
  .use(FloatingVue)
  .component('font-awesome-icon', FontAwesomeIcon)
  .component('VueSelect', VueSelect)
  .mount('#app');
