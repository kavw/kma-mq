<?php

namespace App\Infra;

final class DataFormats
{
    public const JSON = 'json';

    /**
     * @var array<string, DataFormat>
     */
    private array $formats = [];

    /**
     * @var array<string, DataFormat>
     */
    private array $contentTypes = [];

    /**
     * @param DataFormat[] $formats
     */
    public function __construct(
        array $formats = [
            new DataFormat(self::JSON, 'application/json')
        ]
    ) {
        foreach ($formats as $format) {
            $this->add($format);
        }
    }

    public function getByName(string $name): DataFormat
    {
        return $this->formats[$name]
            ?? throw new \RuntimeException("Unknown format {$name}");
    }

    public function getByType(string $contentType): DataFormat
    {
        return $this->contentTypes[$contentType]
            ?? throw new \RuntimeException("Unknown content-type {$contentType}");
    }

    private function add(DataFormat $format): void
    {
        $this->formats[$format->name] = $format;
        $this->contentTypes[$format->contentType] = $format;
    }
}
