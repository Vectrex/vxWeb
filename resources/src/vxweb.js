import 'vite/modulepreload-polyfill';
import { createApp } from 'vue';

import Login from '../vue/app/login.vue';
import './index.css';

createApp(Login).mount('body');
