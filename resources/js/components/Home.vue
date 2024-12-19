<template>
    <div class="container text-center mt-5">
        <h1 class="display-4">Welcome to BookExchange!</h1>
        <p class="lead">Exchange books with other users and discover new stories.</p>
        <div class="mt-4">
            <router-link to="/books" class="btn btn-primary me-2">Explore Books</router-link>
            <router-link to="/login" class="btn btn-outline-primary" v-if="!isAuthenticated">Login</router-link>
            <router-link to="/register" class="btn btn-outline-primary" v-if="!isAuthenticated">Register</router-link>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isAuthenticated: !!localStorage.getItem("token"),
        };
    },
};
</script>

<style scoped>
h1 {
    font-size: 3rem;
    font-weight: bold;
    color: #343a40;
}

p {
    font-size: 1.2rem;
}
</style>
