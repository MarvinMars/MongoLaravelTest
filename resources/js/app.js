require('./style/bootstrap');
import vuetify from './style/vuetify'

import axios from 'axios';
import Vuex from 'vuex';
import Swal from 'sweetalert2'
import 'sweetalert2/src/sweetalert2.scss'
import * as VueGoogleMaps from 'vue2-google-maps'

window.Vue = require('vue');
window.axios = axios;
window.Swal = Swal;

Vue.use(Vuex);

/*File with routes*/
import router from './router/router'

/*Added headers for axios*/
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept':'application/json',
    'Content-type':'application/json'
};

Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.MIX_GOOGLE_MAP_API,
        libraries: 'places',
    },
    installComponents: true,
});

const app = new Vue({
    el: '#app',
    vuetify,
    router,
});
