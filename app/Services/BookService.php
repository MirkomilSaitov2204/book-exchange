<?php

namespace App\Services;

use App\Interfaces\BookInterface;
use App\Models\Book;
use Illuminate\Http\Request;

class BookService implements BookInterface
{

    public function __construct(private readonly Book $book)
    {

    }

    public function query()
    {
        return $this->book->query();
    }

    public function filter($builder, Request $request)
    {
        return $builder->when($request->title, fn($query) => $query->where('title', 'like', "%{$request->title}%"))
            ->when($request->author, fn($query) => $query->where('author', 'like', "%{$request->author}%"))
            ->when($request->genre, fn($query) => $query->where('genre', $request->genre));
    }

    public function paginate($builder, $paginate = 10)
    {
        return $builder->paginate($paginate);
    }

    public function store($request)
    {
        $created = $request->all();
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('book_images', 'public');
        }

        $created['cover_image'] = $imagePath;
        return auth()->user()->books()->create($created);
    }

    public function update($book, $request)
    {
        $book = $this->query()->where('id', $book->id)->first();

        $updated = $request->all();

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('book_images', 'public');
        }

        $updated['cover_image'] = $imagePath;

        return $book->update($updated);
    }

    public function find(int $id)
    {
        return $this->query()->find($id);
    }
}
