<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Register</h2>
                <form @submit.prevent="register">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input v-model="form.name" type="text" id="name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input v-model="form.email" type="email" id="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input v-model="form.password" type="password" id="password" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" id="password_confirmation" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const form = ref({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

        const error = ref('');

        const register = async () => {
            try {
                await axios.post('/api/register', form.value);
                window.location.href = '/login';
            } catch (err) {
                error.value = 'Registration failed. Please check your input.';
            }
        };

        return { form, error, register };
    }
};
</script>
