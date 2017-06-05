# GraphQL package

## Configuration
```yaml
graphql:
    mutation:
        class: MyApp\GraphQL\Type\MutationType
        fields:
            - '@graphql.field.create_matter'
            - '@graphql.field.add_party_to_matter'
    query:
        class: MyApp\GraphQL\Type\QueryType
        fields:
            - '@graphql.field.matter'
            - '@graphql.field.matter_list'
            - '@graphql.field.ping'
    schema:
        class: MyApp\GraphQL\AppSchema

```

```php
<?php
declare(strict_types=1);

namespace MyApp\GraphQL\Type;

use Pac\GraphQL\AbstractConfigurableFieldType;

class MutationType extends AbstractConfigurableFieldType
{
    public function getName()
    {
        return 'MutationType';
    }
}
```
```php
<?php
declare(strict_types=1);

namespace MyApp\GraphQL\Type;

use Pac\GraphQL\AbstractConfigurableFieldType;

class QueryType extends AbstractConfigurableFieldType
{
    public function getName()
    {
        return 'QueryType';
    }
}
```
```php
<?php
declare(strict_types=1);

namespace MyApp\GraphQL;

use MyApp\GraphQL\Type\MutationType;
use MyApp\GraphQL\Type\QueryType;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;

class AppSchema extends AbstractSchema
{
    public function __construct(QueryType $query, MutationType $mutation)
    {
        $config = [
            'query' => $query,
            'mutation' => $mutation,
            'types' => [],
        ];

        parent::__construct($config);
    }

    public function build(SchemaConfig $config)
    {
        // right now, nothing to do
    }
}
```
