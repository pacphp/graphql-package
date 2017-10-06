<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Pac\GraphQL\Entity\FileInterface;
use Psr\Http\Message\UploadedFileInterface;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\Scalar\DateTimeTzType;
use Youshido\GraphQL\Type\Scalar\StringType;

class InputFileType extends AbstractInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     */
    public function build($config)
    {
        $config
            ->addFields(
                [
                    'file'       =>  [
                        'type'    => new FileType(),
                        'resolve' => function (FileInterface $value, array $args = [], ResolveInfo $info) {
                    return 'do shit';
                        },
                    ],
                    'id'         => new StringType(),
                    'label'      => new StringType(),
                    'uploadedAt' => new DateTimeTzType(),
                ]
            );
    }

    public function isValidValue($value)
    {
        return true;
        if (! $value instanceof UploadedFileInterface) {
            return false;
        }

        return true;
    }
}
