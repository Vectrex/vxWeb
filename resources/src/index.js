import './index.css'
import { createApp } from 'vue'
import router from './router'
import fetch from '@/plugins/fetch';
import App from './App.vue'

const host = 'http://test.leia/admin/'

createApp(App)
    .use(router)
    .use(fetch, { router: router, authFailureRoute: { name: 'login' } })
    .provide('api', host)
    .mount('#app')
