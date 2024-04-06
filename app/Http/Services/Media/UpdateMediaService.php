<?php

namespace App\Http\Services\Media;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateMediaService
{
    public function update(int $id, array $data)
    {
        $repository = new MediaRepository();

        $media = $repository->getById($id);

        $oldFileName = "public/medias/{$media->filename}";

        $file = data_get($data, 'media');
        $extension = $file->getClientOriginalExtension();
        $name = Str::uuid()->toString() . ".{$extension}";

        $data['extension'] = $extension;
        $data['filename'] = $name;
        $data['size'] = $file->getSize();

        $updated = $repository->update($media, $data);

        unset($data['media']);

        $file->storeAs('public/medias', $name);

        if ($updated) {
            Storage::delete($oldFileName);
        }

        return $updated;
    }
}
