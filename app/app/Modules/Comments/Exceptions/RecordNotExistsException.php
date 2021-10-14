<?php

namespace App\Modules\Comments\Exceptions;

class RecordNotExistsException extends Exception
{
    public static function byId(int $id): self
    {
        return new static(sprintf("Can't find comment in storage. Comment id:[%d]", $id));
    }
}
