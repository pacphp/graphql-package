<?php
declare(strict_types=1);

namespace Pac\GraphQL\Entity;

use DateTime;

interface FileInterface
{
    public function getFileName(): string;

    public function setFileName(string $fileName);

    public function getId(): string;

    public function getPath(): string;

    public function setPath(string $path);

    public function getMimeType(): string;

    public function setMimeType(string $mimeType);

    public function getUploadedAt(): DateTime;

    public function setUploadedAt(DateTime $uploadedAt);

    public function getSize(): int;

    public function setSize(int $size);

    public function getExtension(): string;

    public function setExtension(string $extension);
}
