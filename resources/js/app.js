import { createApp } from 'vue';
import router from './router';
import App from './components/App.vue';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: false,
    wssPort: 6001,
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,  // Pass the token in the WebSocket request
        },
    },
});

axios.defaults.baseURL = 'http://127.0.0.1/api';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.withCredentials = true;

axios.get('/sanctum/csrf-cookie').then(() => {

    const token = localStorage.getItem('token');
    console.log(token);
    if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    const app = createApp(App);
    app.use(router);
    app.mount('#app');
}).catch(error => {
    console.error('CSRF token error:', error);
});
