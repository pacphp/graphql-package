<?php
declare(strict_types=1);

namespace Pac\GraphQL\Query;

use Pac\GraphQL\Type\PingType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class Ping extends AbstractField
{
    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new PingType();
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return new PingType();
    }
}
