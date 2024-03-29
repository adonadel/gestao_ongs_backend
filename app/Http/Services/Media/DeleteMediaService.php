<?php

namespace App\Http\Services\Media;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;

class DeleteMediaService
{
    public function delete($id)
    {
        $repository = new MediaRepository();

        $media = $repository->getById($id);

        Storage::delete($media->filename);

        return $media->delete();
    }
}
