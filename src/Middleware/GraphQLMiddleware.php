<?php
declare(strict_types=1);

namespace Pac\GraphQL\Middleware;

use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Pac\Middleware\IdentityMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Youshido\GraphQL\Execution\Container\Container;
use Youshido\GraphQL\Execution\Context\ExecutionContext;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\AbstractSchema;
use Zend\Diactoros\Response\JsonResponse;

class GraphQLMiddleware implements MiddlewareInterface
{
    protected $schema;

    public function __construct(AbstractSchema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        try {
            $content = json_decode($request->getBody()->getContents(), true);
            $content += [
                'query'     => null,
                'variables' => null,
            ];

            $container = (new Container())
                ->set('user', $request->getAttribute(IdentityMiddleware::IDENTITY_ATTRIBUTE));
            $context = (new ExecutionContext($this->schema))
                ->setContainer($container);
            $result = (new Processor($context))
                ->processPayload($content['query'], $content['variables'])
                ->getResponseData();

        } catch (Exception $e) {
            $result = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        $response = new JsonResponse($result);

        return $response;
    }
}