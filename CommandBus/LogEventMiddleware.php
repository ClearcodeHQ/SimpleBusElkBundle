<?php

namespace Clearcode\ElkBridgeBundle\CommandBus;

use Clearcode\ElkBridgeBundle\Converter\ObjectToArrayConverter;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogEventMiddleware implements MessageBusMiddleware
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ObjectToArrayConverter
     */
    private $converter;

    /**
     * LogEventMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, ObjectToArrayConverter $converter)
    {
        $this->logger    = $logger;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->logger->info('Event recorded', ['event' => $this->converter->toArray($message)]);

        $next($message);
    }
}
