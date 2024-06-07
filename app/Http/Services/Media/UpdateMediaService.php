<?php

namespace App\Http\Services\Media;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;

class UpdateMediaService
{
    public function update(int $id, array $data)
    {
        $repository = new MediaRepository();

        $media = $repository->getById($id);

        if (data_get($data, 'media')) {
            $oldFileName = $media->filename;

            $data = (new CreateMediaService())->makeUpload($data);

            Storage::disk('google')->delete($oldFileName);
        }
        
        $updated = $repository->update($media, $data);

        return $updated;
    }
}
