<?php

namespace App\Enums;

enum ResponseEnum: string
{
    case POLICY_ABORT = '権限がありません。';
    case QUESTION_CREATED = '問題を追加しました。';
    case QUESTION_UPDATED = '問題を更新しました。';
    case QUESTION_DELETED = '問題を削除しました。';
    case ITEM_CREATED = '項目を追加しました。';
    case ITEM_UPDATED = '項目を更新しました。';
    case ITEM_DELETED = '項目を削除しました。';
}
