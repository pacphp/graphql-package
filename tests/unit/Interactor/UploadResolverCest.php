<?php
declare(strict_types=1);

namespace Test\Unit\Interactor;

use Helper\UploadFileTrait;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Pac\GraphQL\Interactor\Uploader;
use Pac\GraphQL\Interactor\UploadResolver;
use Ramsey\Uuid\Uuid;
use UnitTester;

class UploadResolverCest
{
    use UploadFileTrait;

    public function _before(UnitTester $I)
    {
        $this->setupUploadFile();
    }

    public function testResolveToFileType(UnitTester $I)
    {
        $filesystem = new Filesystem(new MemoryAdapter());
        $uploader = new Uploader($filesystem);
        $uploadResolver = new UploadResolver($uploader);

        $id = Uuid::uuid4()->getHex();
        $fileData = $uploadResolver->resolveToArray($this->uploadedFile, ['id' => $id]);

        $expected = [
            'extension' => 'tiff',
            'filename' => $id . '.earth.tiff',
            'id' => $id,
            'label' => 'earth',
            'mimeType' => 'image/tiff',
            'path' => '.',
            'size' => 786572,
            'uploadedAt' => $fileData['uploadedAt'],
        ];

        $I->assertSame($expected, $fileData);
    }
}
