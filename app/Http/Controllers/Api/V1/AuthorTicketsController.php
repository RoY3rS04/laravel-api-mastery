<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class AuthorTicketsController extends ApiController
{
    public function index(string $author_id, TicketFilter $filters) {
        return TicketResource::collection(
            Ticket::where('user_id', $author_id)
                ->filter($filters)
                ->paginate()
        );
    }

    public function store(string $author_id, StoreTicketRequest $request)
    {
        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $author_id, string $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id === (int) $author_id) {
                $ticket->delete();
                return $this->ok("Ticket successfully deleted");
            }

            return $this->error(
                'Ticket cannot be found',
                Response::HTTP_NOT_FOUND
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                'Ticket cannot be found',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, int $author_id, int $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id === $author_id) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket->fresh());
            }

        } catch (ModelNotFoundException $e) {
            return $this->error(
                'Ticket cannot be found',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function replace(ReplaceTicketRequest $request, int $author_id, int $ticket_id) {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id !== $author_id) {
                // TODO: ticket doesn't belong to user
            }

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error(
                'Ticket cannot be found',
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
