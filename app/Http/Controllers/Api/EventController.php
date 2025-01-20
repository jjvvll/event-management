<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use \Illuminate\Routing\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
class EventController extends Controller
{
    use CanLoadRelationships;
    private $relations = ['user', 'attendees', 'attendees.user'];
    use AuthorizesRequests;

    public function __construct(){

      $this->authorizeResource(Event::class, 'event');
        //$this->authorize('viewAny', Event::class);
    }

    public function index()
    {

       // $this->authorize('viewAny', Event::class);

        $query =  $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate());

        // return EventResource::collection( resource:
        // Event::with('user')->paginate());
    }


    /**
     * Store a newly created reso,,,urce in storage.
     */
    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $validatedData['user_id'] = $request->user()->id;

        $event = Event::create($validatedData);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
       // $event->load('user' ,'attendees');
       return new EventResource( $this->loadRelationships($event));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        // if(Gate::denies('update-event', $event)){
        //     abort(403, 'You are not authorized to update this event.');
        // }

        //$this->authorize('update-event', $event);

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

        $validatedData['user_id'] = $request->user()->id;

        $event->update( $validatedData);

        return new EventResource($this->loadRelationships($event));

        //$event = Event::create($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response( status: 204);

        // return response()->json([/
        //   /// //// 'message' => 'Event deleted successfully'//
        // ]);
    }
}
