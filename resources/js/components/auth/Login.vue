<template>
    <div class="container mt-5">
        <h2>Login</h2>
        <form @submit.prevent="login">
            <div class="mb-3">
                <label>Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input v-model="form.password" type="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: { email: "", password: "" },
        };
    },
    methods: {
        login() {
            axios
                .post("/login", this.form)
                .then((response) => {
                    localStorage.setItem("token", response.data.token);
                    axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.token}`;
                    this.$router.push("/");
                })
                .catch((error) => {
                    alert("Invalid credentials");
                    console.error(error);
                });
        },
    },
};
</script>
