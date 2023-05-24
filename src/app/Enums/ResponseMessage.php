<?php

namespace App\Enums;

enum ResponseMessage: string
{
    case LOGGED_IN = 'ログインにしました。';
    case LOGGED_OUT = 'ログアウトしました。';
    case ALREADY_LOGGED_OUT = 'すでにログアウトしています';
    case POLICY_ABORT = '権限がありません。';
    case CREATED = '登録に成功しました。';
    case UPDATED = '更新に成功しました。';
    case DELETED = '削除に成功しました。';
}
