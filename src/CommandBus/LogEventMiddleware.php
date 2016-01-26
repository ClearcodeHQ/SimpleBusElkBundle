<?php

namespace Clearcode\SimpleBusElkBundle\CommandBus;

use Clearcode\SimpleBusElkBundle\Logstash\CannotWriteToLogstash;
use Clearcode\SimpleBusElkBundle\Logstash\Logstash;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogEventMiddleware implements MessageBusMiddleware
{
    /** @var Logstash */
    private $logstash;
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param Logstash        $logstash
     * @param LoggerInterface $logger
     */
    public function __construct(Logstash $logstash, LoggerInterface $logger)
    {
        $this->logstash = $logstash;
        $this->logger   = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        try {
            $this->logstash->write($message);
        } catch (CannotWriteToLogstash $e) {
            $this->logger->error($e->getMessage());
        }

        $next($message);
    }
}
