<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login</h2>
                <form @submit.prevent="login" class="card p-4 shadow-sm">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input v-model="form.email" type="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input v-model="form.password" type="password" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: { email: '', password: '' },
        };
    },
    methods: {
        login() {
            axios
                .post('/login', this.form)
                .then(response => {
                    const { token, user } = response.data;

                    localStorage.setItem('token', token);
                    localStorage.setItem('user_id', user.id);

                    this.isAuthenticated = true;
                    this.userId = user.id;

                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                    this.$router.push('/');
                })
                .catch(error => {
                    alert('Invalid credentials');
                    console.error(error);
                });
        },
    },
};
</script>

<style scoped>
.card {
    border-radius: 0.5rem;
}
h2 {
    color: #343a40;
}
</style>
