<?php

namespace Gelf;

interface IMessagePublisher
{
    /**
     * Publishes a Message, returns false if an error occured during write
     *
     * @throws  \UnexpectedValueException
     * @param Message $message
     * @return boolean
     */
    public function publish(Message $message);
}
