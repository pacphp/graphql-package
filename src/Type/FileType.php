<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Pac\GraphQL\Entity\FileInterface;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class FileType extends AbstractObjectType
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
}
