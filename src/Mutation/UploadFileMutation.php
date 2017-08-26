<?php
declare(strict_types=1);

namespace Pac\GraphQL\Mutation;

use League\Flysystem\File;
use Pac\GraphQL\Interactor\UploadResolver;
use Pac\GraphQL\Type\FileType;
use Pac\GraphQL\Type\UploadedFileType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class UploadFileMutation extends AbstractField
{
    /** @var string */
    protected $fieldName;
    protected $resolver;

    public function __construct(UploadResolver $resolver)
    {
        parent::__construct();
        $this->resolver = $resolver;
    }
//    public function __construct( $fieldName = 'file')
//    {
//        $this->fieldName = $fieldName;
//        parent::__construct([
//            'name' => $fieldName,
//            'type' => $this->getType(),
//        ]);
//    }

    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('upload', new UploadedFileType());
    }

    /**
     * @param mixed       $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return File
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $this->resolver->resolveProperty($args['upload'], 'upload');
    }

    public function getName()
    {
        return 'uploadFile';
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new FileType();
    }
}
