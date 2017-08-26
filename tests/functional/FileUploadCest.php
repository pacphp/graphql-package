<?php
declare(strict_types=1);

namespace Test\Functional;

use FunctionalTester;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Oscar\GraphQL\Field\PingField;
use Pac\GraphQL\Interactor\Uploader;
use Pac\GraphQL\Interactor\UploadResolver;
use Pac\GraphQL\Mutation\UploadFileMutation;
use Pac\GraphQL\Schema;
use Pac\GraphQL\Type\MutationType;
use Pac\GraphQL\Type\QueryType;
use Youshido\GraphQL\Execution\Processor;
use Zend\Diactoros\UploadedFile;

class FileUploadCest
{
    protected $tmpFile;

    public function _before(FunctionalTester $I)
    {
        $imageFile = __DIR__ . '/../_data/upload/phpjBhmXi';
        $this->tmpFile = __DIR__ . '/../_data/upload/tmp/phpjBhmXi';
        if (! file_exists(__DIR__ . '/../_data/upload/tmp/')) {
            mkdir(__DIR__ . '/../_data/upload/tmp/', 0777, true);
        }
        copy($imageFile, $this->tmpFile);
    }

    public function testMultipartUpload(FunctionalTester $I)
    {
        $adapter = new MemoryAdapter();
        $filesystem = new Filesystem($adapter);
        $uploader = new Uploader($filesystem);
        $resolver = new UploadResolver($uploader);
        $mutations = [
            new UploadFileMutation($resolver),
        ];
        $query = new QueryType();
        $query->addReferences($mutations);
        $mutation = new MutationType();
        $mutation->addReferences($mutations);
        $schema = new Schema($query, $mutation);

        $file = new UploadedFile(
            $this->tmpFile,
            786572,
            0,
            'earth.tiff',
            'image/tiff'
        );
        $content = [
            'query'     => "mutation uploadFile(\$upload: UploadedFile!) {\n  uploadFile(upload: \$upload) {\n    id\n    label\n    mimeType\n    size\n    __typename\n  }\n}\n",
            'variables' => [
                'upload' => $file,
            ],
        ];
        $result = (new Processor($schema))
            ->processPayload($content['query'], $content['variables'])
            ->getResponseData();

        $expected = [
            'data' => [
                'uploadFile' => [
                    'id'         => $result['data']['uploadFile']['id'],
                    'label'      => 'earth.tiff',
                    'mimeType'   => 'image/tiff',
                    'size'       => 786572,
                    '__typename' => 'File',
                ],
            ],
        ];
        $I->assertSame($expected, $result);
    }
}
