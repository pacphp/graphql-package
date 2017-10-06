<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Http\Factory\Diactoros\UploadedFileFactory;
use Pac\GraphQL\Entity\FileInterface;
use Psr\Http\Message\UploadedFileInterface;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class HttpUploadedFile extends AbstractInputObjectType
{
    public function build($config)
    {
        $config->addFields([
            'extension' => new StringType(),
            'fileName'  => new StringType(),
            'id'        => new IdType(),
            'label'     => new StringType(),
            'mimeType'  => new StringType(),
            'size'      => new IntType(),
            'url'       => [
                'type'    => new StringType(),
                'resolve' => function (FileInterface $value, array $args = [], ResolveInfo $info) {
            return 'url';
                    // return $info->getContainer()->get('graphql_files.upload_graphql_resolver')->resolveWebPath($value);
                },
            ],
        ]);
    }
//
//    public function parseValue($value)
//    {
//        return (new UploadedFileFactory())
//            ->createUploadedFile(
//
//            );
//    }


    public function isValidValue($value)
    {
        if (! $value instanceof UploadedFileInterface) {
            return false;
        }

        return true;
    }
}
