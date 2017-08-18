<?php
declare(strict_types=1);

namespace Pac\GraphQL\Middleware;

use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Pac\Middleware\IdentityMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Youshido\GraphQL\Execution\Container\Container;
use Youshido\GraphQL\Execution\Context\ExecutionContext;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\AbstractSchema;
use Zend\Diactoros\Response\JsonResponse;

class GraphQLMiddleware implements MiddlewareInterface
{
    protected $debug;
    /** @var LoggerInterface */
    protected $logger;
    /** @var AbstractSchema */
    protected $schema;

    public function __construct(AbstractSchema $schema, LoggerInterface $logger = null, bool $debug = false)
    {
        $this->debug = $debug;
        $this->logger = $logger;
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

            if ($this->logger && $this->debug) {
                $this->logger->info('GraphQL');
                $this->logger->info('=======');
                $this->logger->info('Query');
                $this->logger->info(json_encode($content['query']));
                $this->logger->info('Variables');
                $this->logger->info(json_encode($content['variables']));
                $this->logger->info('=======');
            }

            $result = (new Processor($this->schema))
                ->processPayload($content['query'], $content['variables'])
                ->getResponseData();

        } catch (Exception $e) {
            $result = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            if ($this->logger) {
                $this->logger->error($e->getMessage());
            }
        }

        $response = new JsonResponse($result);

        return $response;
    }
}
