<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;


class BookController extends Controller
{
    public function __construct(private readonly BookInterface $book)
    {
    }

    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $builder = $this->book->query();

        $builder = $this->book->filter(builder: $builder, request: $request);

        $books = $this->book->paginate(builder: $builder, paginate: 30);

        return BookResource::collection($books);
    }

    public function store(BookRequest $request): BookResource
    {
        return new BookResource($this->book->store($request));
    }

    public function show(int $id)
    {
        return new BookResource($this->book->find(id: $id));
    }

    public function update(BookRequest $request, Book $book): BookResource
    {
        $this->authorize('update', $book);

        $this->book->update($book, $request);

        return new BookResource($book);
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->noContent();
    }
}
