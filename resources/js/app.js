import './bootstrap';

import mitt from 'mitt';
import axios from 'axios';
import router from '@/router';
import App from '@/App.vue';
import iziToast from 'izitoast';
import { createApp } from 'vue';

import authMixins from '@/mixins/authMixins.js';

import { myToast } from '@/assets/myToast.js';

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const emitter = mitt();

const app = createApp(App);

app.use(router);
app.component('font-awesome-icon', FontAwesomeIcon);
app.mixin(authMixins);

app.config.globalProperties.$axios = axios;
app.config.globalProperties.$emitter = emitter;
app.config.globalProperties.$iziToast = iziToast;
app.config.globalProperties.$myToast = myToast;

app.mount("#tri_p2p_wallet");
