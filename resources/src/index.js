import './index.css'
import { createApp } from 'vue'
import router from './router'
import App from './App.vue'

const host = 'http://test.leia/admin/'

createApp(App)
    .use(router)
    .provide('api', host)
    .mount('#app')
