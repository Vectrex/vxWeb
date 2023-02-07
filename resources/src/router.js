import { createRouter, createWebHistory } from 'vue-router';
import Login from "@/components/views/Login.vue";
import Users from "@/components/views/Users.vue";
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
            component: Profile,
            meta: {
                label: 'Mein Profil'
            }
        },
        {
            name: 'users',
            path: '/users',
            component: Users,
            meta: {
                label: 'Benutzer'
            }
        }
    ]
});

router.beforeEach (async (to, from) => {
});

export default router;
