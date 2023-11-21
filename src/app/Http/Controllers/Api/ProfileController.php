<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveRequest $request
     */
    public function update(SaveRequest $request): Response
    {
        $user = auth()->user();
        $data = $request->all();

        DB::transaction(static function () use ($data, $user) {
            $user->fill($data)->save();
        });

        return response()->json(['message' => ResponseMessage::UPDATED->value], Response::HTTP_OK);
    }
}
