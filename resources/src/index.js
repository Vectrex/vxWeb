import './index.css'
import { createApp } from 'vue'
import router from './router'
import fetch from '@/plugins/fetch'
import promisedXhr from '@/plugins/promisedXhr'
import App from './App.vue'

const host = 'http://test.leia/admin/'

const app = createApp(App);
app
    .use(router)
    .use(fetch, { router: router, authFailureRoute: { name: 'login' } })
    .use(promisedXhr)
    .provide('api', host)
    .mount('#app')
;
app.config.unwrapInjectedRef = true;
