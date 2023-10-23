<?php

namespace App\Domain\Services;

final readonly class FileLinkProvider implements LinkProvider
{
    public function __construct(
        private string $filePath
    ) {
    }

    /**
     * @return \Generator<string>
     */
    public function getLinks(): \Generator
    {
        if (!is_file($this->filePath)) {
            throw new \RuntimeException("Given path {$this->filePath} is not a file");
        }

        if (!is_readable($this->filePath)) {
            throw new \RuntimeException("Can't read the give file {$this->filePath}");
        }

        $file = fopen($this->filePath, 'r+');
        if (!$file) {
            throw new \RuntimeException("Can't open file {$this->filePath}");
        }

        try {
            while (($line = fgets($file)) !== false) {
                $url = trim($line);
                if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
                    continue;
                }

                yield $url;
            }
        } finally {
            fclose($file);
        }
    }
}
