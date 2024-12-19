<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h2">Books</h1>
            <router-link to="/books/create" class="btn btn-success">Add Book</router-link>
        </div>

        <div class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input
                        type="text"
                        v-model="filters.title"
                        class="form-control"
                        placeholder="Search by Title"
                    />
                </div>
                <div class="col-md-4">
                    <input
                        type="text"
                        v-model="filters.author"
                        class="form-control"
                        placeholder="Search by Author"
                    />
                </div>
                <div class="col-md-4">
                    <input
                        type="text"
                        v-model="filters.genre"
                        class="form-control"
                        placeholder="Search by Genre"
                    />
                </div>
            </div>
            <button class="btn btn-primary mt-3" @click="fetchBooks">Search</button>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="book in books" :key="book.id" class="col">
                <div class="card h-100">
                    <img :src="book.cover_image || 'https://via.placeholder.com/150'" class="card-img-top" alt="Book Image" />

                    <div class="card-body">
                        <h5 class="card-title">{{ book.title }}</h5>
                        <p class="card-text">
                            <strong>Author:</strong> {{ book.author }} <br>
                            <strong>Genre:</strong> {{ book.genre }}
                        </p>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div v-if="isOwner(book)">
                            <button class="btn btn-danger btn-sm" @click="deleteBook(book.id)">Delete</button>
                            <router-link :to="`/books/${book.id}/edit`" class="btn btn-secondary btn-sm">Edit</router-link>
                        </div>

                        <button
                            v-else
                            class="btn btn-sm"
                            :class="book.requested ? 'btn-secondary' : 'btn-primary'"
                            :disabled="book.requested"
                            @click="sendRequest(book)"
                        >
                            {{ book.requested ? 'Requested' : 'Request' }}
                        </button>
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
            books: [],
            filters: {
                title: '',
                author: '',
                genre: '',
            },
        };
    },
    methods: {
        fetchBooks() {
            const params = {
                title: this.filters.title,
                author: this.filters.author,
                genre: this.filters.genre,
            };

            axios.get('/books', { params }).then((response) => {
                this.books = response.data.data;
            });
        },
        deleteBook(id) {
            axios.delete(`/books/${id}`).then(() => {
                this.fetchBooks();
            });
        },
        sendRequest(book) {
            axios.post('/requests', { book_id: book.id }).then(() => {
                book.requested = true;
            }).catch((error) => {
                alert('Failed to send request: ' + error.response.data.message);
            });
        },
        isOwner(book) {
            return book.user_id === book.auth_id;
        },
    },
    mounted() {
        this.fetchBooks();
    },
};
</script>

<style scoped>
.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>
