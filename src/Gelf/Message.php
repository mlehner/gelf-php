<?php

namespace Gelf;

class Message {
    /**
     * @var string
     */
    private $version = '1.0';

    /**
     * @var integer
     */
    private $timestamp = 0;

    /**
     * @var string
     */
    private $shortMessage = '';

    /**
     * @var string
     */
    private $fullMessage = '';

    /**
     * @var string
     */
    private $facility = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var integer
     */
    private $level = 0;

    /**
     * @var string
     */
    private $file = '';

    /**
     * @var integer
     */
    private $line = 0;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param string $version
     * @return Message
     */
    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @param integer $timestamp
     * @return Message
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @param string $shortMessage
     * @return Message
     */
    public function setShortMessage($shortMessage) {
        $this->shortMessage = $shortMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortMessage() {
        return $this->shortMessage;
    }

    /**
     * @param string $fullMessage
     * @return Message
     */
    public function setFullMessage($fullMessage) {
        $this->fullMessage = $fullMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullMessage() {
        return $this->fullMessage;
    }

    /**
     * @param string $facility
     * @return Message
     */
    public function setFacility($facility) {
        $this->facility = $facility;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacility() {
        return $this->facility;
    }

    /**
     * @param string $host
     * @return Message
     */
    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param integer $level
     * @return Message
     */
    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * @param string $file
     * @return Message
     */
    public function setFile($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param integer $line
     * @return Message
     */
    public function setLine($line) {
        $this->line = $line;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLine() {
        return $this->line;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Message
     */
    public function setAdditional($key, $value) {
        if ($key == 'id')
        {
            throw new \InvalidArgumentException('The "id" additional field is not allowed.');
        }

        $this->data["_" . trim($key)] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditional($key) {
        return isset($this->data["_" . trim($key)]) ? $this->data[$key] : null;
    }

    /**
     * @return array
     */
    public function toArray() {
        // Format according to specification: http://graylog2.org/gelf#specs
        $messageAsArray = array(
            'version' => $this->getVersion(),
            'timestamp' => $this->getTimestamp(),
            'short_message' => $this->getShortMessage(),
            'full_message' => $this->getFullMessage(),
            'host' => $this->getHost(),
            'level' => $this->getLevel(),
            // In v1.1 of spec, the following are deprecated.
            '_file' => $this->getFile(),
            '_facility' => $this->getFacility(),
            '_line' => $this->getLine(),
        );

        foreach($this->data as $key => $value) {
            $messageAsArray[$key] = $value;
        }

        return $messageAsArray;
    }
}
