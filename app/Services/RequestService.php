<?php

namespace App\Services;

use App\Interfaces\RequestInterface;
use App\Models\Book;
use App\Models\Request;
use App\Notifications\BookRequestedNotification;
use Illuminate\Support\Facades\Auth;

class RequestService implements RequestInterface
{
    public function __construct(private readonly Request $model, private readonly Book $book)
    {
    }

    public function query()
    {
        return $this->model->query();
    }

    public function get($query)
    {
        $query = $query->with('book', 'requester');

        $incomingRequests = (clone $query)->whereHas('book', function ($query){
            $query->where('user_id', auth()->id());
        })->get();

        $outgoingRequests = (clone $query)->where('requester_id', auth()->id())->get();

        return [$incomingRequests, $outgoingRequests];
    }

    public function store($request)
    {
        $book = $this->book->find($request->book_id);

        if ($book->user_id === auth()->id())
            return response()->json(['message' => "You can't request your own book."], 403);

        $existingRequest = $this->query()->where('book_id', $book->id)
            ->where('requester_id', auth()->id())
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You have already sent a request for this book.'], 400);
        }

        $requested =  $this->query()->create([
            'book_id' => $book->id,
            'requester_id' => auth()->id(),
            'status' => 'Pending',
        ]);

        $book->user->notify(new BookRequestedNotification($book, Auth::user()));

        return $requested;
    }
}
