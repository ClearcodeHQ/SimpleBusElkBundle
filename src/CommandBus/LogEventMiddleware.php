<?php

namespace Clearcode\SimpleBusElkBundle\CommandBus;

use Clearcode\SimpleBusElkBundle\Converter\ObjectToArrayConverterInterface;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogEventMiddleware implements MessageBusMiddleware
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ObjectToArrayConverterInterface
     */
    private $converter;

    /**
     * LogEventMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, ObjectToArrayConverterInterface $converter)
    {
        $this->logger    = $logger;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        try {
            $this->logger->info('Event recorded', $this->converter->toArray($message));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $next($message);
    }
}
