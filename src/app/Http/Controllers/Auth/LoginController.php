<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class LoginController extends Controller
{
    /**
     * @param AuthManager $auth
     */
    public function __construct(
        private AuthManager $auth,
    ) {
    }

    /**
     * @param LoginRequest $request
     * @throws AuthenticationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        if ($this->auth->guard()->attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();
            // 最終ログイン日時を更新する
            DB::transaction(static function () use ($user) {
                $user->fill(['last_login_at' => now()])->save();
            });

            return new JsonResponse([
                'message' => ResponseMessage::LOGGED_IN->value,
            ]);
        }

        throw new AuthenticationException();
    }
}
