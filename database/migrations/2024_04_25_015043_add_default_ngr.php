<?php

use App\Models\Address;
use App\Models\Ngr;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $address = Address::create([
            'street' => 'Avenida Centenário',
            'neighborhood' => 'Centro',
            'city' => 'Criciúma',
            'state' => 'SC',
            'number' => '1000',
        ]);

        Ngr::create([
            'name' => 'Patinhas Carentes',
            'cnpj' => '84.058.017/0001-90',
            'description' => 'Ong que sonha em fazer a diferença na vida dos animais necessitados na cidade de Criciúma',
            'address_id' => $address->id
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $model = Ngr::whereName('Patinhas Carentes')->first();

        if ($model) {
            $model->address->delete();
            $model->delete();
        }
    }
};
