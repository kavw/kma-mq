<?php

namespace App\Infra;

use App\Infra;

final readonly class DataFormat
{
    public function __construct(
        public string $name,
        public string $contentType
    ) {
    }
}
