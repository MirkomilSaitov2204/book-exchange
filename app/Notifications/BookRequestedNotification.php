<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;


class BookRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $book;
    protected $requester;

    /**
     * Create a new notification instance.
     */
    public function __construct(Book $book, $requester)
    {
        $this->book = $book;
        $this->requester = $requester;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for storing in the database.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'book_name' => $this->book->title,
            'author' => $this->book->author,
            'date' => now()->format('Y-m-d H:i:s'),
            'status' => 'Pending',
            'requester_name' => $this->requester->name,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'book_name' => $this->book->title,
            'author' => $this->book->author,
            'date' => now()->format('Y-m-d H:i:s'),
            'status' => 'Pending',
            'requester_name' => $this->requester->name,
        ];
    }

    public function broadcastOn()
    {
//        return new PrivateChannel('user.' . $this->notifiable->id);
    }
}
