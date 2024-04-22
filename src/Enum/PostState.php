<?php

namespace App\Enum;

enum PostState: string
{
    case Draft = 'DRAFT';
    case Published = 'PUBLISHED';
    case Deleted = 'DELETED';
}
