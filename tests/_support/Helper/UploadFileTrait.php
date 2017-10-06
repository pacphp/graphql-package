<?php
declare(strict_types=1);

namespace Helper;

use Zend\Diactoros\UploadedFile;

trait UploadFileTrait
{
    protected $tmpFile;
    /** @var UploadedFile */
    protected $uploadedFile;

    public function setupUploadFile()
    {
        $uploadDir = __DIR__ . '/../../_data/upload/';
        $imageFile = $uploadDir . 'phpjBhmXi';
        $this->tmpFile = $uploadDir . 'tmp/phpjBhmXi';
        if (! file_exists($uploadDir . 'tmp/')) {
            mkdir($uploadDir . 'tmp/', 0777, true);
        }
        copy($imageFile, $this->tmpFile);

        $this->uploadedFile = new UploadedFile(
            $this->tmpFile,
            786572,
            0,
            'earth.tiff',
            'image/tiff'
        );
    }
}
