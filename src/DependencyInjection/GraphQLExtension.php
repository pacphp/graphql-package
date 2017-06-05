<?php
declare(strict_types=1);

namespace Pac\GraphQL\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

class GraphQLExtension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = call_user_func_array('array_merge', $configs);

        $fieldReferences = [];
        foreach($config['mutation']['fields'] as $fieldId) {
            $fieldReferences[] = new Reference(implode('', explode('@', $fieldId, 2)));
        }
        $container
            ->register('graphql.mutation_type', $config['mutation']['class'])
            ->addMethodCall('addReferences', [$fieldReferences]);

        $fieldReferences = [];
        foreach($config['query']['fields'] as $fieldId) {
            $fieldReferences[] = new Reference(implode('', explode('@', $fieldId, 2)));
        }
        $container
            ->register('graphql.query_type', $config['query']['class'])
            ->addMethodCall('addReferences', [$fieldReferences]);

        $container
            ->register('graphql.app_schema', $config['schema']['class'])
            ->addArgument(new Reference('graphql.query_type'))
            ->addArgument(new Reference('graphql.mutation_type'));

    }

    public function getNamespace()
    {
        return 'graphql';
    }

    public function getXsdValidationBasePath()
    {
        // TODO: Implement getXsdValidationBasePath() method.
    }

    public function getAlias()
    {
        return 'graphql';
    }
}
