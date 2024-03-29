<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\Precedent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PrecedentController extends Controller
{
    /**
     * 事例を削除する。
     *
     * @param Precedent $precedent
     */
    public function destroy(Precedent $precedent): JsonResponse
    {
        $precedent->delete();

        return response()->json(['message' => ResponseMessage::DELETED->value], Response::HTTP_OK);
    }
}
