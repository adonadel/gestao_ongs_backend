<?php

namespace App\Http\Services\Media;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;

class CreateMediaService
{

    public function create(array $data)
    {
        $repository = new MediaRepository();

        $data = $this->makeUpload($data);

        return $repository->create($data);
    }

    public function bulkCreate(array $medias)
    {
        $allMedias = [];

        foreach ($medias as $data) {
            $allMedias[] = $this->create($data);
        }

        return $allMedias;
    }

    public function makeUpload(array $data): array
    {
        $file = data_get($data, 'media');
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();

        $data['extension'] = $extension;
        $data['size'] = $file->getSize();
        unset($data['media']);

        $filename = Storage::disk('google')->put('', $file, [
            'visibility' => 'public',
            'mimeType' => $mimeType,
        ]);

        $data['filename_id'] = data_get(
            Storage::disk('google')->getAdapter()->getMetadata($filename)->extraMetadata(),
            'id'
        );
        
        $data['filename'] = $filename;

        return $data;
    }
}
