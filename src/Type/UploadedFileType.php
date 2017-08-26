<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Psr\Http\Message\UploadedFileInterface;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
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
                    'file'    => new StringType(),
                ]
            );
    }

    public function isValidValue($value)
    {
        if (! $value instanceof UploadedFileInterface) {
            return false;
        }

        return true;
    }
}
