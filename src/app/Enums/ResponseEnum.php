<?php

namespace App\Enums;

enum ResponseEnum: string
{
    case POLICY_ABORT = '権限がありません。';
    case QUESTION_CREATED = '問題を追加しました。';
    case QUESTION_UPDATED = '問題を更新しました。';
    case QUESTION_DELETED = '問題を削除しました。';
}
