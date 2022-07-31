<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuestionController;
use App\Models\Question;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    // ログインユーザー情報
    Route::get('/profile', ProfileController::class);

    Route::get('/questions', [QuestionController::class, 'index'])->can('viewAny', Question::class);
    Route::post('/questions', [QuestionController::class, 'store'])->can('create', Question::class);
    Route::post('/questions/import', [QuestionController::class, 'importCSV']);
    Route::patch('/questions/{question}', [QuestionController::class, 'update'])->can('update', Question::class);
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->can('delete', Question::class);
});
