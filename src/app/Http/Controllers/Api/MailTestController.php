<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class MailTestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $name = 'テストユーザー';
        $mail = 'example@example.com';

        Mail::send(new TestMail($name, $mail));

        return response()->json(['メールを送信しました。', Response::HTTP_OK]);
    }
}
