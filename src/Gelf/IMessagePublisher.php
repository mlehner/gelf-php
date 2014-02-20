<?php

namespace Gelf;

interface IMessagePublisher {
    
    /**
     * @var string
     */
    const GRAYLOG2_PROTOCOL_VERSION = '1.0';
    
    /**
     * Publishes a Message, returns false if an error occured during write
     *
     * @throws UnexpectedValueException
     * @param Gelf\Message $message
     * @return boolean
     */
    public function publish(Message $message);
}
