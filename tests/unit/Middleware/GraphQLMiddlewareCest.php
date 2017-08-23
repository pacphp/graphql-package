<?php
declare(strict_types=1);

namespace Test\Unit\Middleware;

use Http\Factory\Diactoros\StreamFactory;
use Pac\GraphQL\Middleware\GraphQLMiddleware;
use ReflectionClass;
use UnitTester;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\UploadedFile;

class GraphQLMiddlewareCest
{
    public function testExtractContentFromJsonRequest(UnitTester $I)
    {
        $expected = [
            'query'     => 'query getMatter($id: String!) {\n matter(id: $id) {\nid\n}\n}',
            'variables' => [
                'id' => '4d967a0f65224f1685a602cbe4eef667',
            ],
        ];
        $jsonContent = json_encode($expected);
        $stream = (new StreamFactory)->createStream($jsonContent);
        $request = new ServerRequest(
            [],
            [],
            null,
            null,
            $stream
        );

        $rc = new ReflectionClass(GraphQLMiddleware::class);
        $middleware = $rc->newInstanceWithoutConstructor();
        $rm = new \ReflectionMethod(GraphQLMiddleware::class, 'extractContentFromRequest');
        $rm->setAccessible(true);
        $content = $rm->invoke($middleware, $request);

        $I->assertSame($expected, $content);
    }

    public function testExtractContentFromMultipartRequest(UnitTester $I)
    {
        $post = [
            'query'     => '"mutation uploadFile($upload: UploadedFile!) {\n  uploadFile(upload: $upload) {\n    id\n    name\n    type\n    size\n    path\n    __typename\n  }\n}\n"',
            'variables' => json_encode(
                [
                    'value' => 'Some value passed in',
                ]
            ),
        ];
        $request = new ServerRequest(
            [],
            [],
            null,
            null,
            'php://input',
            [],
            [],
            [],
            $post
        );

        $rc = new ReflectionClass(GraphQLMiddleware::class);
        $middleware = $rc->newInstanceWithoutConstructor();
        $rm = new \ReflectionMethod(GraphQLMiddleware::class, 'extractContentFromRequest');
        $rm->setAccessible(true);
        $content = $rm->invoke($middleware, $request);

        $expected = [
            'query'     => "mutation uploadFile(\$upload: UploadedFile!) {\n  uploadFile(upload: \$upload) {\n    id\n    name\n    type\n    size\n    path\n    __typename\n  }\n}\n",
            'variables' => [
                'value' => 'Some value passed in',
            ],
        ];
        $I->assertSame($expected, $content);
    }

    public function testAddFilesToVariablesInMultipartRequest(UnitTester $I)
    {
        $post = [
            'query'     => '"mutation uploadFile($upload: UploadedFile!) {\n  uploadFile(upload: $upload) {\n    id\n    name\n    type\n    size\n    path\n    __typename\n  }\n}\n"',
            'variables' => json_encode(
                [
                    'value' => 'Some value passed in',
                ]
            ),
        ];
        $file = new UploadedFile(
            __DIR__ . '/../../_data/upload-tmp/phpjBhmXi',
            786572,
            0,
            'earth.tiff',
            'image/tiff'
        );
        $request = new ServerRequest(
            [],
            ['upload' => $file],
            null,
            null,
            'php://input',
            [],
            [],
            [],
            $post
        );

        $rc = new ReflectionClass(GraphQLMiddleware::class);
        $middleware = $rc->newInstanceWithoutConstructor();
        $rm = new \ReflectionMethod(GraphQLMiddleware::class, 'extractContentFromRequest');
        $rm->setAccessible(true);
        $content = $rm->invoke($middleware, $request);

        $expected = [
            'query'     => "mutation uploadFile(\$upload: UploadedFile!) {\n  uploadFile(upload: \$upload) {\n    id\n    name\n    type\n    size\n    path\n    __typename\n  }\n}\n",
            'variables' => [
                'value'  => 'Some value passed in',
                'upload' => $file,
            ],
        ];
        $I->assertSame($expected, $content);
    }
}
