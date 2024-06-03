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

    public function bulkCreate(array $data)
    {
        $allMedias = [];

        foreach ($data['medias'] as $media) {
            $allMedias[] = $this->create($media);
        }

        return $allMedias;
    }

    public function makeUpload(array $data): array
    {
        $file = data_get($data, 'media');
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $imageDetails = getimagesize($file->getPathname());

        $data['extension'] = $extension;
        $data['size'] = $file->getSize();
        $data['width'] = $imageDetails[0];
        $data['height'] = $imageDetails[1];
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
