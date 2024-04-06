<?php

namespace App\Http\Services\Adoption;

use App\Enums\AdoptionsStatusEnum;
use App\Repositories\AdoptionRepository;

class ChangeAdoptionStatusService
{
    private AdoptionsStatusEnum $status;

    public function __construct(AdoptionsStatusEnum $status)
    {
        $this->status = $status;
    }

    public function changeStatus(int $id)
    {
        $adoption = (new AdoptionRepository())->getById($id);

        return $adoption->update([
            'status' => $this->status
        ]);
    }
}
