import { createRouter, createWebHistory } from 'vue-router';
import Login from "@/components/views/Login.vue";
import Profile from "@/components/views/Profile.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'login',
            path: '/',
            component: Login
        },
        {
            name: 'profile',
            path: '/profile',
            component: Profile
        }
    ]
});

router.beforeEach (async (to, from) => {
});

export default router;
