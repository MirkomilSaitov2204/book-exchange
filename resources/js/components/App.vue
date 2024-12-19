<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <router-link to="/" class="navbar-brand">BookExchange</router-link>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link to="/books" class="nav-link">Books</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/requests" class="nav-link">Requests</router-link>
                    </li>
                    <li class="nav-item dropdown" v-if="isAuthenticated">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Notifications <span class="badge bg-danger">{{ notificationCount }}</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li v-for="(notification, index) in notifications" :key="index">
                                <a class="dropdown-item" href="#">
                                    <strong>{{ notification.book_name }}</strong> has been requested by {{ notification.requester_name }}
                                    <small class="text-muted">{{ notification.date }}</small>
                                </a>
                            </li>
                            <li v-if="notifications.length === 0"><a class="dropdown-item text-muted">No notifications</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <router-link to="/login" class="nav-link" v-if="!isAuthenticated">Login</router-link>
                        <router-link to="/register" class="nav-link" v-if="!isAuthenticated">Register</router-link>
                        <a href="#" class="nav-link" v-else @click="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container mt-4">
            <router-view />
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            isAuthenticated: false,
            notificationCount: 0,
            notifications: [],
            userId: null,
        };
    },
    methods: {
        listenForNotifications() {
            window.Echo.private(`user.${this.userId}`)
                .listen('BookRequested', (event) => {
                    console.log('New notification received:', event);
                    this.notifications.unshift({
                        book_name: event.book_name,
                        requester_name: event.requester_name,
                        date: event.date,
                    });
                    this.notificationCount++;
                });
        },

        fetchNotifications() {
            axios.get('/api/notifications').then(response => {
                this.notifications = response.data.notifications || [];
                this.notificationCount = response.data.unread_count || 0;
            }).catch(error => {
                console.error('Error fetching notifications:', error);
            });
        },

        logout() {
            axios.post('/logout').then(() => {
                this.isAuthenticated = false;
                localStorage.removeItem('token');
                this.$router.push('/login');
            }).catch(error => {
                console.error('Logout failed:', error);
            });
        },
    },
    mounted() {
        this.isAuthenticated = !!localStorage.getItem('token');
        if (this.isAuthenticated) {
            this.fetchNotifications();
        }
    },

    watch: {
        notifications(newNotifications) {
            this.notificationCount = newNotifications.length;
        },
    },
};

</script>

<style scoped>
body {
    background-color: #f8f9fa;
}

.navbar-nav .nav-link {
    color: #fff;
}

.navbar-nav .nav-link:hover {
    color: #ddd;
}

.modal-content {
    border-radius: 8px;
}

.list-group-item {
    padding: 12px;
    font-size: 0.9rem;
}

.badge {
    font-size: 1.1rem;
    padding: 0.4em;
}

h3 {
    margin-top: 1.5rem;
}
</style>
