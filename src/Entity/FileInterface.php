<?php
declare(strict_types=1);

namespace Pac\GraphQL\Entity;

use DateTime;

interface FileInterface
{
    public function getExtension(): string;

    public function getFileName(): string;

    public function getId(): string;

    public function getMimeType(): string;

    public function getPath(): string;

    public function getSize(): int;

    public function getSystemFilename(): string;

    public function getUploadedAt(): DateTime;
}
