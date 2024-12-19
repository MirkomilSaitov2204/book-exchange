<template>
    <div class="container-fluid  mt-5 row">
        <div class="col-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>User Profile</h2>
                </div>
                <div class="card-body">
                    <div v-if="loading" class="text-center text-muted">Loading...</div>
                    <form v-else @submit.prevent="updateProfile">
                        <div class="mb-3">
                            <label class="form-label">Avatar:</label>
                            <div class="mb-2">
                                <img :src="profile.avatarUrl" alt="User Avatar" v-if="profile.avatar" class="img-thumbnail"/>
                            </div>
                            <input type="file" @change="onFileChange" class="form-control"/>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input v-model="profile.name" type="text" id="name" class="form-control" required/>
                            <div class="invalid-feedback">
                                Please provide a name.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input v-model="profile.email" type="email" id="email" class="form-control" required/>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input v-model="profile.location" type="text" id="location" class="form-control"/>
                            <div class="invalid-feedback">
                                Please provide a location.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>

                    <div v-if="successMessage" class="alert alert-success mt-3">{{ successMessage }}</div>
                    <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>
                </div>
            </div>

        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h2>Change Password</h2>
                </div>
                <div class="card-body">
                    <form @submit.prevent="changePassword" class="needs-validation" novalidate>
                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password:</label>
                            <input v-model="passwords.currentPassword" type="password" id="currentPassword" class="form-control" required/>
                            <div class="invalid-feedback">
                                Please provide your current password.
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password:</label>
                            <input v-model="passwords.newPassword" type="password" id="newPassword" class="form-control" required/>
                            <div class="invalid-feedback">
                                Please provide a new password.
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">Confirm New Password:</label>
                            <input v-model="passwords.confirmNewPassword" type="password" id="confirmNewPassword" class="form-control" required/>
                            <div class="invalid-feedback">
                                Please confirm your new password.
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>

                    <div v-if="passwordSuccessMessage" class="alert alert-success mt-3">{{ passwordSuccessMessage }}</div>
                    <div v-if="passwordErrorMessage" class="alert alert-danger mt-3">{{ passwordErrorMessage }}</div>
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
            profile: {
                name: '',
                email: '',
                location: '',
                avatar: '',
                avatarUrl: '',
            },
            file: null,
            loading: true,
            successMessage: '',
            errorMessage: '',
            passwords: {
                currentPassword: '',
                newPassword: '',
                confirmNewPassword: '',
            },
            passwordSuccessMessage: '',
            passwordErrorMessage: '',
        };
    },
    methods: {
        fetchProfile() {
            this.loading = true;
            axios
                .get('/api/user', {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                })
                .then((response) => {
                    console.log(response.data);
                    const { name, email, location, avatar } = response.data;
                    this.profile = { name, email, location, avatar, avatarUrl: `/storage/${avatar}` };
                })
                .catch((error) => {
                    this.errorMessage = 'Failed to load profile. Please try again.';
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        updateProfile() {
            const formData = new FormData();
            formData.append('name', this.profile.name);
            formData.append('email', this.profile.email);
            formData.append('location', this.profile.location);
            if (this.file) {
                formData.append('avatar', this.file);
            }
            axios
                .post('/api/user', formData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(() => {
                    this.successMessage = 'Profile updated successfully!';
                    this.fetchProfile();
                })
                .catch((error) => {
                    this.errorMessage = 'Failed to update profile. Please check your input.';
                });
        },
        onFileChange(event) {
            this.file = event.target.files[0];
        },
        changePassword() {
            if (this.passwords.newPassword !== this.passwords.confirmNewPassword) {
                this.passwordErrorMessage = 'Passwords do not match.';
                return;
            }

            axios
                .post('/api/user/password', {
                    current_password: this.passwords.currentPassword,
                    new_password: this.passwords.newPassword,
                }, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                })
                .then(() => {
                    this.passwordSuccessMessage = 'Password changed successfully!';
                    this.passwordErrorMessage = '';
                    this.passwords = {
                        currentPassword: '',
                        newPassword: '',
                        confirmNewPassword: '',
                    };
                })
                .catch((error) => {
                    this.passwordErrorMessage = 'Failed to change password. Please check your input.';
                });
        },
    },
    mounted() {
        this.fetchProfile();
    },
};
</script>

<style>
.img-thumbnail {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.card {
    margin-bottom: 20px;
}
</style>
