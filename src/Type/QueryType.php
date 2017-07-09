<?php
declare(strict_types=1);

namespace Pac\GraphQL\Type;

use Pac\GraphQL\AbstractConfigurableFieldType;

class QueryType extends AbstractConfigurableFieldType
{
    public function getName()
    {
        return 'Query';
    }
}
