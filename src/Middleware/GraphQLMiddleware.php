<?php
declare(strict_types=1);

namespace Pac\GraphQL\Middleware;

use Exception;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\AbstractSchema;
use Zend\Diactoros\Response\JsonResponse;

class GraphQLMiddleware implements MiddlewareInterface
{
    protected $debug;
    protected $contextServices;
    /** @var LoggerInterface */
    protected $logger;
    /** @var AbstractSchema */
    protected $schema;

    public function __construct(AbstractSchema $schema, array $contextServices = [], LoggerInterface $logger = null, bool $debug = false)
    {
        $this->debug = $debug;
        $this->contextServices = $contextServices;
        $this->logger = $logger;
        $this->schema = $schema;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        try {
            $content = $this->extractContentFromRequest($request);

            if ($this->logger && $this->debug) {
                $this->logger->info('GraphQL');
                $this->logger->info('=======');
                $this->logger->info('Query');
                $this->logger->info(json_encode($content['query']));
                $this->logger->info('Variables');
                $this->logger->info(json_encode($content['variables']));
                $this->logger->info('=======');
            }

            $processor = new Processor($this->schema);
            $this->injectServicesInContext($processor);
            $result = $processor
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

    protected function extractContentFromRequest(ServerRequestInterface $request): array
    {
        if (!$content = json_decode($request->getBody()->getContents(), true)) {
            $content = $request->getParsedBody();
            foreach ($content as $key => $json) {
                $content[$key] = json_decode($json, true);
            }
            $content['variables'] += $request->getUploadedFiles();
        }

        $content += [
            'query'     => null,
            'variables' => null,
        ];

        return $content;
    }

    protected function injectServicesInContext(Processor $processor)
    {
        $container = $processor->getExecutionContext()->getContainer();
        foreach ($this->contextServices as $id => $service) {
            $container->set($id, $service);
        }
    }

}
