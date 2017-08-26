<?php
declare(strict_types=1);

namespace Pac\GraphQL\Entity;

use DateTime;

class File implements FileInterface
{
    /** @var string */
    protected $extension;
    /** @var string */
    protected $filename;
    /** @var string */
    protected $id;
    /** @var string */
    protected $label;
    /** @var string */
    protected $mimeType;
    /** @var string */
    protected $path;
    /** @var int */
    protected $size;
    /** @var DateTime */
    protected $uploadedAt;

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getUploadedAt(): DateTime
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(DateTime $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }
}
