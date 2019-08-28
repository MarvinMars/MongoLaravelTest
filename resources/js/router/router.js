import Vue from 'vue'
import Router from 'vue-router'

import Dashboard from '../components/App/Dashboard'

import Cars from '../components/Cars/Index'
import Car from '../components/Cars/Single'

Vue.component('dashboard', Dashboard);
Vue.component('cars', Cars);
Vue.component('car', Car);
Vue.use(Router);

export default new Router({
    routes: [
        {
            name: 'dashboard',
            path: '/',
        },
        {
            name: 'car',
            path: '/car/:carId',
            component: Car,
        },
        {
            name: 'cars',
            path: '/cars',
            component: Cars,
        }
    ]
})
