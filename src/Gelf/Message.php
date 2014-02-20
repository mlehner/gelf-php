<?php

namespace Gelf;

class Message
{
    /**
     * @var string
     */
    private $version = '1.0';

    /**
     * @var integer
     */
    private $timestamp = null;

    /**
     * @var string
     */
    private $shortMessage = null;

    /**
     * @var string
     */
    private $fullMessage = null;

    /**
     * @var string
     */
    private $facility = null;

    /**
     * @var string
     */
    private $host = null;

    /**
     * @var integer
     */
    private $level = null;

    /**
     * @var string
     */
    private $file = null;

    /**
     * @var integer
     */
    private $line = null;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param string $version
     * @return Message
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param integer $timestamp
     * @return Message
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $shortMessage
     * @return Message
     */
    public function setShortMessage($shortMessage)
    {
        $this->shortMessage = $shortMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortMessage()
    {
        return $this->shortMessage;
    }

    /**
     * @param string $fullMessage
     * @return Message
     */
    public function setFullMessage($fullMessage)
    {
        $this->fullMessage = $fullMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullMessage()
    {
        return $this->fullMessage;
    }

    /**
     * @param string $facility
     * @return Message
     */
    public function setFacility($facility)
    {
        $this->facility = $facility;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * @param string $host
     * @return Message
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param integer $level
     * @return Message
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $file
     * @return Message
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param integer $line
     * @return Message
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws \InvalidArgumentException
     * @return Message
     */
    public function setAdditional($key, $value)
    {
        $key = $this->prepareKey($key);

        if ($key === '_id') {
            throw new \InvalidArgumentException('The "id" additional field is not allowed.');
        }

        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getAdditional($key)
    {
        $key = $this->prepareKey($key);
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $messageAsArray = array(
          'version' => $this->getVersion(),
          'timestamp' => $this->getTimestamp(),
          'short_message' => $this->getShortMessage(),
          'full_message' => $this->getFullMessage(),
          'facility' => $this->getFacility(),
          'host' => $this->getHost(),
          'level' => $this->getLevel(),
          'file' => $this->getFile(),
          'line' => $this->getLine(),
        );

        return array_replace($messageAsArray, $this->data);
    }

    /**
     * Returns the key for a Additional Value.
     *
     * @param string $key
     * @return string
     */
    private function prepareKey($key)
    {
        return '_' . trim($key);
    }
}
