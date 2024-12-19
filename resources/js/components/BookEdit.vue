<template>
    <div>
        <h1>Edit Book</h1>
        <form @submit.prevent="updateBook">
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
                <div v-if="form.cover_image_url">
                    <img :src="form.cover_image_url" alt="Current Book Cover" class="mt-2" style="max-width: 150px;" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: {
                title: "",
                author: "",
                genre: "",
                condition: "",
                cover_image: null,
                cover_image_url: "",
            },
        };
    },
    methods: {
        fetchBook() {
            axios.get(`/books/${this.$route.params.id}`).then((response) => {
                const book = response.data.data;
                this.form.title = book.title;
                this.form.author = book.author;
                this.form.genre = book.genre;
                this.form.condition = book.condition;
                this.form.cover_image_url = book.cover_image;
            });
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            this.form.cover_image = file;
        },

        updateBook() {
            const formData = new FormData();
            formData.append("title", this.form.title);
            formData.append("author", this.form.author);
            formData.append("genre", this.form.genre);
            formData.append("condition", this.form.condition);


            if (this.form.cover_image) {
                formData.append("cover_image", this.form.cover_image);
            }

            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            axios
                .post(`/books/${this.$route.params.id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("Book updated successfully", response.data);
                    this.$router.push("/books");
                })
                .catch((error) => {
                    console.error("Error updating book:", error);
                });
        },
    },
    mounted() {
        this.fetchBook();
    },
};
</script>
