<?php

namespace App\Http\Controllers;


use App\Http\Requests\EventRequest;
use App\Http\Services\Event\CreateEventService;
use App\Http\Services\Event\DeleteEventService;
use App\Http\Services\Event\QueryEventService;
use App\Http\Services\Event\UpdateEventService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function create(EventRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateEventService();

            $event = $service->create($request->all());

            DB::commit();

            return $event;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(EventRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $service = new UpdateEventService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new DeleteEventService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Evento excluído com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function getEvents(Request $request)
    {
        $service = new QueryEventService();

        return $service->getEvents($request->all());
    }

    public function getEventById(int $id)
    {
        $service = new QueryEventService();

        return $service->getEventById($id);
    }
}
