import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/views/Login.vue'
import Profile from "@/components/views/Profile.vue"
import Users from "@/components/views/Users.vue"

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
            component:  Profile,
            meta: {
                heading: 'Meine Einstellungen'
            }
        },
        {
            name: 'articles',
            path: '/articles',
            component:  Users,
            meta: {
                label: 'Artikel'
            }
        },
        {
            name: 'files',
            path: '/files',
            component:  Users,
            meta: {
                label: 'Dateien'
            }
        },
        {
            name: 'pages',
            path: '/pages',
            component:  Users,
            meta: {
                label: 'Seiten'
            }
        },
        {
            name: 'users',
            path: '/users',
            component:  Users,
            meta: {
                label: 'Benutzer'
            }
        }
    ]
});

router.beforeEach (async (to, from) => {
});

export default router;
