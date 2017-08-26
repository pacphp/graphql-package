<?php
declare(strict_types=1);

namespace Pac\GraphQL\Interactor;

use League\Flysystem\Filesystem;
use Psr\Http\Message\StreamInterface;

class Uploader
{
    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function upload(string $filename, StreamInterface $stream): bool
    {
        return $this->fileSystem->write($filename, $stream);
    }
}
