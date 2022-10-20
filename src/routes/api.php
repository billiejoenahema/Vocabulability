<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\MailTestController;
use App\Http\Controllers\Api\PrecedentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuestionController;
use App\Models\Item;
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

    // メール送信
    Route::get('/test-mail', MailTestController::class);

    // 定数
    Route::get('/const', fn () => config('const'));

    // 問題
    Route::get('/questions', [QuestionController::class, 'index'])->can('viewAny', Question::class);
    Route::post('/questions', [QuestionController::class, 'store'])->can('create', Question::class);
    Route::post('/questions/import', [QuestionController::class, 'importCSV'])->can('create', Question::class);
    Route::patch('/questions/{question}', [QuestionController::class, 'update'])->can('update', Question::class);
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->can('delete', Question::class);

    // 項目
    Route::get('/items', [ItemController::class, 'index'])->can('viewAny', Item::class);
    Route::post('/items', [ItemController::class, 'store'])->can('create', Item::class);
    Route::post('/items/import', [ItemController::class, 'importCSV'])->can('create', Item::class);
    Route::patch('/items/{item}', [ItemController::class, 'update'])->can('update', Item::class);
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->can('delete', Item::class);
    // 事例
    Route::delete('/precedents/{precedent}', [PrecedentController::class, 'destroy'])->can('delete', Item::class);
});
