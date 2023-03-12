import { createRouter, createWebHistory } from 'vue-router'
import { UsersIcon, NewspaperIcon, DocumentIcon, PhotoIcon } from '@heroicons/vue/24/solid'
const Login = () => import('@/components/views/Login.vue')
const Profile  = () => import("@/components/views/Profile.vue")
const Users  = () => import("@/components/views/Users.vue")
const Files  = () => import("@/components/views/Files.vue")
const Articles  = () => import("@/components/views/Articles.vue")
const ArticleEdit  = () => import("@/components/views/ArticleEdit.vue")

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
                heading: 'Meine Einstellungen'
            }
        },
        {
            name: 'files',
            path: '/files/:path*',
            component: Files,
            meta: {
                label: 'Dateien',
                icon: PhotoIcon
            }
        },
        {
            name: 'articles',
            path: '/articles',
            component: Articles,
            meta: {
                label: 'Artikel',
                icon: NewspaperIcon
            }
        },
        {
            name: 'articleEdit',
            path: '/article/:id?',
            component: ArticleEdit,
            props: true
        },
        {
            name: 'pages',
            path: '/pages',
            component: Users,
            meta: {
                label: 'Seiten',
                icon: DocumentIcon
            }
        },
        {
            name: 'users',
            path: '/users',
            component:  Users,
            meta: {
                label: 'Benutzer',
                icon: UsersIcon
            }
        }
    ]
});

router.beforeEach (async (to, from) => {
});

export default router;
