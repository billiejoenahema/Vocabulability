<?php

namespace App\Enums;

enum QuestionEnum: string
{
    case CREATED_MESSAGE = '問題を追加しました。';
    case UPDATED_MESSAGE = '問題を更新しました。';
    case DELETED_MESSAGE = '問題を削除しました。';
}
