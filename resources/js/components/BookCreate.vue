<template>
    <div>
        <h1>Add Book</h1>
        <form @submit.prevent="addBook">
            <div class="mb-3">
                <label>Title</label>
                <input v-model="form.title" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Author</label>
                <input v-model="form.author" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Genre</label>
                <input v-model="form.genre" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Condition</label>
                <input v-model="form.condition" class="form-control" required />
            </div>
            <!-- Image Upload -->
            <div class="mb-3">
                <label>Book Image</label>
                <input type="file" @change="handleFileUpload" class="form-control" />
            </div>
            <button type="submit" class="btn btn-success">Add Book</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                title: '',
                author: '',
                genre: '',
                condition: '',
                cover_image: null,
            },
        };
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.form.cover_image = file;
        },

        addBook() {
            const formData = new FormData();

            formData.append('title', this.form.title);
            formData.append('author', this.form.author);
            formData.append('genre', this.form.genre);
            formData.append('condition', this.form.condition);

            if (this.form.cover_image) {
                formData.append('cover_image', this.form.cover_image);
            }

            axios
                .post('/books', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(() => {
                    this.$router.push('/books');
                })
                .catch((error) => {
                    console.error('There was an error uploading the book:', error);
                });
        },
    },
};
</script>
