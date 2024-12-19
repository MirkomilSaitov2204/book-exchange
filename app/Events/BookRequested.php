<?php

namespace App\Events;

use App\Models\Book;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param Book $book
     * @param User $requester
     */
    public function __construct(private readonly Book $book, private readonly User $requester)
    {
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user.' . $this->requester->id);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => "Your book '{$this->book->title}' has been requested.",
            'book_name' => $this->book->title,
            'book_id' => $this->book->id,
            'author' => $this->book->author,
            'date' => now()->format('Y-m-d H:i:s'),
            'status' => 'Pending',
        ];
    }
}
