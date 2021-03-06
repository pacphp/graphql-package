<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\DateTimeTzType;
use Youshido\GraphQL\Type\Scalar\StringType;

class UploadedFileType extends AbstractInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     */
    public function build($config)
    {
        $config
            ->addFields(
                [
                    'id'         => new StringType(),
                    'label'      => new StringType(),
                    'uploadedAt' => new DateTimeTzType(),
                ]
            );
    }

    public function isValidValue($value)
    {
        return true;
    }
}
