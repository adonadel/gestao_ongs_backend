<?php

namespace App\Http\Services\Media;

use App\Repositories\AnimalMediaRepository;
use App\Repositories\EventMediaRepository;
use App\Repositories\MediaRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;

class UpdateMediaService
{
    public function update(int $id, array $data)
    {
        $repository = new MediaRepository();

        $media = $repository->getById($id);

        if (data_get($data, 'is_cover')) {
            $normalized = $this->normalize(data_get($data, 'origin'));

            if ($normalized !== UserRepository::class) {
                $normalized->newQuery()->where('media_id', $media->id)->update(['is_cover' => data_get($data, 'is_cover')]);
            }
        }

        if (data_get($data, 'media')) {
            $oldFileName = $media->filename;

            $data = (new CreateMediaService())->makeUpload($data);

            Storage::disk('google')->delete($oldFileName);
        }

        $updated = $repository->update($media, $data);

        return $updated;
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
