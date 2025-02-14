<?php

namespace App\Enums;

enum Vote: string
{
    case UPVOTE = 'upvote';
    case DOWNVOTE = 'downvote';

    public function label(): string
    {
        return match ($this) {
            self::UPVOTE => 'Upvote',
            self::DOWNVOTE => 'Downvote',
        };
    }
}
