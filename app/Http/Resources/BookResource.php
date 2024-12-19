<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'genre' => $this->genre,
            'condition' => $this->condition,
            'cover_image' =>$this->cover_image ? Storage::url($this->cover_image) : null,
            'owner' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'location' => $this->user->location,
            ],
            'requested' => $this->requests->contains('requester_id', auth()->id()), // Check if user has requested the book
            'user_id' => $this->user_id,
            'auth_id' => auth()->id(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
