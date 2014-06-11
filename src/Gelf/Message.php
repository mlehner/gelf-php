<?php

namespace Gelf;

class Message
{
    /**
     * @var string
     */
    private $version = '1.1';

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
    private $host = null;

    /**
     * @var integer
     */
    private $level = null;

    /**
     * @var array
     */
    private $additionals = array();

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
        $this->setAdditional('facility', $facility);
        return $this;
    }

    /**
     * @return string
     */
    public function getFacility()
    {
        return $this->getAdditional('facility');
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
        $this->setAdditional('file', $file);
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->getAdditional('file');
    }

    /**
     * @param integer $line
     * @return Message
     */
    public function setLine($line)
    {
        $this->setAdditional('line', $line);
        return $this;
    }

    /**
     * @return integer
     */
    public function getLine()
    {
        return $this->getAdditional('line');
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

        if ('_id' == $key) {
            throw new \InvalidArgumentException('The "id" additional field is not allowed.');
        }

        $this->additionals[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getAdditional($key)
    {
        $key = $this->prepareKey($key);
        return isset($this->additionals[$key]) ? $this->additionals[$key] : null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        // This will disallow additionals to overwrite the specified values.
        return array_merge(
          // Filter NULL values.
          array_filter($this->additionals, function($v) { return !is_null($v); }),
          array(
            'version' => $this->getVersion(),
            'short_message' => $this->getShortMessage(),
            'full_message' => $this->getFullMessage(),
            'host' => $this->getHost(),
            // Ensure numeric values.
            'timestamp' => (float)$this->getTimestamp(),
            'level' => (int)$this->getLevel(),
          )
        );
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
