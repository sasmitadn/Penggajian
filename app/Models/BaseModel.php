<?php

namespace App\Models;

use DateTimeInterface;

trait BaseModel
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
