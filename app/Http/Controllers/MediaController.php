<?php

namespace App\Http\Controllers;

use App\Http\Services\Media\CreateMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;

class MediaController extends Controller
{
    function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'media' => [
                    'required', File::types(['jpg', 'jpeg', 'png'])
                ],
                'display_name' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $service = new CreateMediaService();

            $media = $service->create($validated);

            DB::commit();

            return $media;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
