<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequestedRequest;
use App\Interfaces\RequestInterface;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function __construct(private readonly RequestInterface $requestBook)
    {
    }

    public function index()
    {
        $query = $this->requestBook->query();

        list($incomingRequests, $outgoingRequests) = $this->requestBook->get($query);

        return response()->json([
            'incoming_requests' => $incomingRequests,
            'outgoing_requests' => $outgoingRequests,
        ]);
    }

    /**
     * Store a new request for a book.
     */
    public function store(BookRequestedRequest $request)
    {
        $bookRequested = $this->requestBook->store($request);

        return response()->json($bookRequested, 201);
    }

    /**
     * Update the status of a request (approve or reject).
     */
    public function update(Request $request, HttpRequest $requests)
    {
        $this->authorize('update', $request);

        $validated = $requests->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $request->update(['status' => $validated['status']]);

        if ($validated['status'] === 'Approved') {
            $request->book->update(['is_available' => false]);
        }

        return response()->json(['message' => "Request {$validated['status']} successfully."]);
    }
}
