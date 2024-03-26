<?php

use Illuminate\Support\Facades\File as FileForView;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('/api/documentation');
});

Route::get("/medias/{filename}", function ($filename) {

        $path = storage_path("app/public/images/{$filename}");

        if (!file_exists($path)) {
            abort(404);
        }

        $file = FileForView::get($path);
        $type = FileForView::mimeType($path);

        $response = Response::make($file);
        $response->header('Content-Type', $type);

        return $response;
});
