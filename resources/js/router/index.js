import { createRouter, createWebHistory } from 'vue-router';

import Home from '../components/Home.vue';
import Books from '../components/Book.vue';
import BookCreate from '../components/BookCreate.vue';
import BookEdit from '../components/BookEdit.vue';
import Requests from '../components/Requests.vue';
import Notifications from '../components/Notifications.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/books', name: 'Books', component: Books ,  meta: { requiresAuth: true }},
    { path: '/books/create', name: 'BookCreate', component: BookCreate ,  meta: { requiresAuth: true }},
    { path: '/books/:id/edit', name: 'BookEdit', component: BookEdit ,  meta: { requiresAuth: true }},
    { path: '/requests', name: 'Requests', component: Requests ,  meta: { requiresAuth: true }},
    { path: '/notifications', name: 'Notifications', component: Notifications ,  meta: { requiresAuth: true }},
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});


export default router;
