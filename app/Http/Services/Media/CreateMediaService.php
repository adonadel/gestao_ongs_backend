<?php

namespace App\Http\Services\Media;

use App\Repositories\MediaRepository;
use Illuminate\Support\Str;

class CreateMediaService
{

    public function create(array $data)
    {
        $repository = new MediaRepository();

        $file = data_get($data, 'media');
        $extension = $file->getClientOriginalExtension();
        $name = Str::uuid()->toString() . ".{$extension}";

        $data['extension'] = $extension;
        $data['filename'] = $name;
        $data['size'] = $file->getSize();
        unset($data['media']);

        $file->storeAs('public/medias', $name);

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
}
