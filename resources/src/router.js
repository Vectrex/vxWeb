import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/views/Login.vue'
import Profile from "@/components/views/Profile.vue"
import Users from "@/components/views/Users.vue"
import Files from "@/components/views/Files.vue"
import Articles from "@/components/views/Articles.vue"
import ArticleEdit from "@/components/views/ArticleEdit.vue"
import { UsersIcon, NewspaperIcon, DocumentIcon, PhotoIcon } from '@heroicons/vue/24/solid'

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
            name: 'files',
            path: '/files',
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
            path: '/articles/:id',
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
