<?php
declare(strict_types=1);

namespace Pac\GraphQL\Interactor;

use DateTime;
use Pac\GraphQL\Entity\File;
use Pac\GraphQL\Type\FileType;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

class UploadResolver
{
    /** @var string */
    private $fileTypeClass;
    /** @var Uploader */
    private $imageUploader;
    /** @var Uploader */
    private $fileUploader;

    public function __construct(Uploader $fileUploader, Uploader $imageUploader = null)
    {
        $this->fileUploader = $fileUploader;
        $this->imageUploader = $imageUploader;
    }

    public function resolveToArray(UploadedFileInterface $uploadedFile, array $properties = []): array
    {
        $id = $properties['id'] ?? null;
        $filename = $this->storeFile($uploadedFile, $id);
        $fileParts = pathinfo($uploadedFile->getClientFilename());

        return
            [
                'extension'  => $fileParts['extension'],
                'filename'   => $filename,
                'id'         => $id,
                'label'      => $fileParts['filename'],
                'mimeType'   => $uploadedFile->getClientMediaType(),
                'path'       => $fileParts['dirname'],
                'size'       => $uploadedFile->getSize(),
                'uploadedAt' => new DateTime(),
            ] + $properties;
    }

    /**
     * @param mixed  $object
     * @param string $property
     *
     * @return mixed|null
     *
     * @throws \InvalidArgumentException
     */
    public function resolveProperty(UploadedFileInterface $uploadedFile, string $property)
    {
        $id = Uuid::uuid4()->getHex();
        $filename = $id . '.' . $uploadedFile->getClientFilename();
        if (! $this->fileUploader->upload($filename, $uploadedFile->getStream())) {

        }

        $fileParts = pathinfo($uploadedFile->getClientFilename());
        $file = (new File())
            ->setExtension($fileParts['extension'])
            ->setFilename($uploadedFile->getClientFilename())
            ->setLabel(($uploadedFile->getClientFilename()))
            ->setId($id)
            ->setMimeType($uploadedFile->getClientMediaType())
            ->setPath($fileParts['dirname'])
            ->setSize($uploadedFile->getSize())
            ->setUploadedAt(new DateTime());

        return $file;
    }

    /**
     * @param string $requestField
     *
     * @return FileModelInterface
     */
    public function resolveUploadImage(string $requestField)
    {
        return $this->imageUploader->uploadFromUploadedFile($this->getRequestFile($requestField));
    }

    /**
     * @param string $data
     *
     * @return FileModelInterface
     */
    public function resolveUploadBase64Image(string $data)
    {
        return $this->imageUploader->uploadBase64File($data);
    }

    /**
     * @param string $requestField
     *
     * @return FileModelInterface
     */
    public function resolveUploadFile(string $requestField)
    {
        return $this->fileUploader->uploadFromUploadedFile($this->getRequestFile($requestField));
    }

    private function storeFile(UploadedFileInterface $uploadedFile, string $id = null): string
    {
        $id = $id !== null ? $id : Uuid::uuid4()->getHex();
        $filename = $id . '.' . $uploadedFile->getClientFilename();
        if (! $this->fileUploader->upload($filename, $uploadedFile->getStream())) {

        }

        return $filename;
    }
}
