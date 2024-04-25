<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use function Symfony\Component\Translation\t;

enum PostState: string
{
    case Draft = 'DRAFT';
    case Published = 'PUBLISHED';
    case Deleted = 'DELETED';

    public function t(): TranslatableInterface|string
    {
        return t(sprintf('post.field.state.%s', $this->name));
    }
}
