<?php

namespace Clearcode\SimpleBusElkBundle\Logstash;

use Clearcode\SimpleBusElkBundle\Converter\DataToConvertIsNotAnObject;
use Clearcode\SimpleBusElkBundle\Converter\ObjectToArrayConverterInterface;
use Psr\Log\LoggerInterface;

class Logstash
{
    /** @var LoggerInterface */
    private $logger;
    /** @var ObjectToArrayConverterInterface */
    private $converter;

    /**
     * @param LoggerInterface                 $logger
     * @param ObjectToArrayConverterInterface $converter
     */
    public function __construct(LoggerInterface $logger, ObjectToArrayConverterInterface $converter)
    {
        $this->logger = $logger;
        $this->converter = $converter;
    }

    /**
     * @param $object
     *
     * @throws CannotWriteToLogstash
     */
    public function write($object)
    {
        try {
            $this->logger->info('Event recorded', $this->converter->toArray($object));

        } catch (DataToConvertIsNotAnObject $e) {
            $this->handleError(sprintf('Data conversion problem during writing to logstash: %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->handleError(sprintf('Connection with logstash error: %s', $e->getMessage()));
        }
    }

    private function handleError($message)
    {
        throw new CannotWriteToLogstash($message);
    }
}
