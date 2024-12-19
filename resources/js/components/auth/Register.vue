<template>
    <div class="container mt-5">
        <h2>Register</h2>
        <form @submit.prevent="register">
            <div class="mb-3">
                <label>Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input v-model="form.password" type="password" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-success">Register</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: { name: "", email: "", password: "", password_confirmation: "" },
        };
    },
    methods: {
        register() {
            axios
                .post("/register", this.form)
                .then(() => {
                    this.$router.push("/login");
                })
                .catch((error) => {
                    alert("Registration failed");
                    console.error(error);
                });
        },
    },
};
</script>
