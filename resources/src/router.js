import { createRouter, createWebHistory } from 'vue-router';
import Login from "@/components/views/Login.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'login',
            path: '/',
            component: Login
        }
    ]
});

router.beforeEach (async (to, from) => {
});

export default router;
