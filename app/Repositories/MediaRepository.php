<?php

namespace App\Repositories;

use App\Models\Media;

class MediaRepository extends Repository
{

    protected function getModelClass(): string
    {
        return Media::class;
    }

    public function getByFilename(string $filename)
    {
        return $this->newQuery()
            ->where('filename', $filename)
            ->first();
    }
}
