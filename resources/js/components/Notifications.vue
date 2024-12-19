<template>
    <div>
        <h3>Notifications</h3>
        <ul class="list-group">
            <li v-for="notification in notifications" :key="notification.id" class="list-group-item">
                <p><strong>Book:</strong> {{ notification.data.book_name }}</p>
                <p><strong>Author:</strong> {{ notification.data.author }}</p>
                <p><strong>Date:</strong> {{ notification.data.date }}</p>
                <p><strong>Status:</strong> {{ notification.data.status }}</p>
                <p><strong>Requested By:</strong> {{ notification.data.requester_name }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            notifications: [],
        };
    },
    methods: {
        fetchNotifications() {
            axios.get('/api/notifications').then(response => {
                this.notifications = response.data;
            });
        },
    },
    mounted() {
        const userId = localStorage.getItem('user_id');

        this.fetchNotifications();

        window.Echo.private(`books.${userId}`)
            .listen('BookRequestedNotification', (event) => {
                this.notifications.push({
                    data: {
                        book_name: event.book_name,
                        author: event.author,
                        date: event.date,
                        status: event.status,
                        requester_name: event.requester_name,
                    },
                });
            });
    },
};
</script>
