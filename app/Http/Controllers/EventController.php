<?php

namespace App\Http\Controllers;


use App\Http\Requests\EventRequest;
use App\Http\Services\Event\CreateEventService;
use App\Http\Services\Event\DeleteEventService;
use App\Http\Services\Event\QueryEventService;
use App\Http\Services\Event\UpdateEventService;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function create(Request $request)
    {
        Gate::authorize('create', Event::class);

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
                'medias' => 'string|required',
            ]);

            DB::beginTransaction();

            $service = new CreateEventService();

            $event = $service->create($validated);

            DB::commit();

            return $event;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function createWithMedias(EventRequest $request)
    {
        Gate::authorize('create', Event::class);

        try {
            DB::beginTransaction();

            $service = new CreateEventService();

            $event = $service->createWithMedias($request->all());

            DB::commit();

            return $event;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function update(EventRequest $request, int $id)
    {
        Gate::authorize('update', Event::class);

        try {
            DB::beginTransaction();

            $service = new UpdateEventService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        Gate::authorize('delete', Event::class);

        try {
            DB::beginTransaction();

            $service = new DeleteEventService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Evento excluído com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function getEvents(Request $request)
    {
        Gate::authorize('view', Event::class);

        $service = new QueryEventService();

        return $service->getEvents($request->all());
    }

    public function getEventById(int $id)
    {
        Gate::authorize('view', Event::class);

        $service = new QueryEventService();

        return $service->getEventById($id);
    }
}
