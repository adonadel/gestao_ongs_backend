<?php

namespace App\Http\Services\Media;

use App\Repositories\AnimalMediaRepository;
use App\Repositories\EventMediaRepository;
use App\Repositories\MediaRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;

class CreateMediaService
{

    public function create(array $data)
    {
        $repository = new MediaRepository();

        $data = $this->makeUpload($data);

        $media = $repository->create($data);

        if (data_get($media, 'is_cover') && data_get($media, 'is_cover') ===true) {
            $normalized = $this->normalize(data_get($media, 'origin'));

            if ($normalized !== UserRepository::class) {
                $normalized->where('media_id', $media->id)->update(['is_cover' => true]);
            }
        }

        return $media;
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

    private function normalize(string $origin)
    {
        $normalized =  [
            'event' => new EventMediaRepository(),
            'animal' => new AnimalMediaRepository(),
            'user' => new UserRepository(),
        ];

        return $normalized[$origin];
    }
}
