<?php

namespace ProNetwork\Support\Enums;

enum ReactionType: string
{
    case LIKE = 'like';
    case LOVE = 'love';
    case CELEBRATE = 'celebrate';
    case INSIGHTFUL = 'insightful';
    case SUPPORT = 'support';
    case CURIOUS = 'curious';
    case DISLIKE = 'dislike';
}
