// import _ from 'lodash';
// window._ = _;

import 'bootstrap';
import axios from 'axios';
import router from '@/router';
import 'bootstrap/dist/css/bootstrap.min.css';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core';

/* import specific icons */
import { faTimes } from '@fortawesome/free-solid-svg-icons';
import { faClock } from '@fortawesome/free-regular-svg-icons';

import 'izitoast/dist/css/iziToast.min.css';

/* add icons to the library */
library.add(faClock, faTimes);

router.beforeEach((to, from, next) => {
    document.title = to.meta.title || 'TRI P2P Wallet';

    next();
});

axios.defaults.baseURL = 'http://localhost:8000/api/v1';
axios.defaults.headers.common['Content-Type'] = 'application/json';
