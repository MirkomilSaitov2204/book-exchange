<template>
    <div class="container mt-4">
        <h1 class="h2 mb-4">Exchange Requests</h1>

        <h3 class="mt-4">Incoming Requests</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="request in incomingRequests" :key="request.id" class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Book: {{ request.book.title }}</h5>
                        <p class="card-text">
                            <strong>Requester:</strong> {{ request.requester.name }} <br>
                            <strong>Status:</strong>
                            <span class="badge" :class="badgeClass(request.status)">
                                {{ request.status }}
                            </span>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between" v-if="request.status === 'Pending'">
                        <button class="btn btn-success btn-sm" @click="updateRequest(request.id, 'Approved')">Approve</button>
                        <button class="btn btn-danger btn-sm" @click="updateRequest(request.id, 'Rejected')">Reject</button>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5">Outgoing Requests</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="request in outgoingRequests" :key="request.id" class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Book: {{ request.book.title }}</h5>
                        <p class="card-text">
                            <strong>Status:</strong>
                            <span class="badge" :class="badgeClass(request.status)">
                                {{ request.status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5">Exchange History</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="request in exchangeHistory" :key="request.id" class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Book: {{ request.book.title }}</h5>
                        <p class="card-text">
                            <strong>Exchanged With:</strong> {{ request.requester.name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            incomingRequests: [],
            outgoingRequests: [],
            exchangeHistory: [],
        };
    },
    methods: {
        fetchRequests() {
            axios.get('/requests').then(response => {
                this.incomingRequests = response.data.incoming_requests;
                this.outgoingRequests = response.data.outgoing_requests;
                this.exchangeHistory = response.data.exchange_history;
            });
        },
        updateRequest(id, status) {
            axios.put(`/requests/${id}`, { status }).then(() => {
                this.fetchRequests();
            }).catch(error => {
                alert('Failed to update request: ' + error.response.data.message);
            });
        },
        badgeClass(status) {
            return {
                'bg-warning': status === 'Pending',
                'bg-success': status === 'Approved',
                'bg-danger': status === 'Rejected',
            };
        },
    },
    mounted() {
        this.fetchRequests();
    },
};
</script>

<style scoped>
.card-img-top {
    height: 200px;
    object-fit: cover;
}
.badge {
    font-size: 0.9rem;
    padding: 0.5em;
}
h3 {
    margin-top: 1.5rem;
}
</style>
