import { createWebHistory, createRouter } from 'vue-router';
import store from '../stores/index';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import("../pages/Home.vue"),
        meta: {
            middleware: ['auth'],
            title: 'Home',
        }
    },
    {
        path: '/create-task',
        name: 'CreateTask',
        component: () => import("../pages/CreateTask.vue"),
        meta: {
            middleware: ['auth'],
            title: 'CreateTask',
        }
    },
    {
        path: '/detail-task/:id',
        name: 'DetailTask',
        component: () => import("../pages/DetailTask.vue"),
        meta: {
            middleware: ['auth'],
            title: 'Detail Task',
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import("../pages/Login.vue"),
        meta: {
            middleware: ['guest'],
            title: 'Login',
        }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import("../pages/Register.vue"),
        meta: {
            middleware: ['guest'],
            title: 'Register',
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware.includes('guest')) {
        if (store.getters['Authenticated']) {
            next({ name: 'Home' });
        } else {
            next();
        }
    } else if (to.meta.middleware.includes('auth')) {
        if (store.getters['Authenticated']) {
            next();
        } else {
            next({ name: "Login" });
        }
    } else {
        next();
    }
});

export default router;