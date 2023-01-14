<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SaveRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(SaveRequest $request)
    {
        $user = auth()->user();
        $data = $request->all();

        DB::transaction(function () use ($data, $user) {
            $user->fill($data)->save();
        });

        return response()->json(['message' => ResponseEnum::UPDATED->value], Response::HTTP_OK);
    }
}
