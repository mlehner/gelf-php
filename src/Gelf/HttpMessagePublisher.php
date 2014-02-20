<?php

namespace Gelf;

use InvalidArgumentException;
use RuntimeException;

class HttpMessagePublisher implements IMessagePublisher {

  
    const DEFAULT_PORT = 12202;
    const DEFAULT_HOST = "127.0.0.1";
    const DEFAULT_PATH = "/gelf";
    
    /**
     * The host the HTTP receiver is on
     * 
     * @var string
     */
    protected $host;
    
    /**
     * The port the HTTP receiver listens on
     * 
     * @var int
     */
    protected $port;
    
    /**
     * The URL path the reciver listens on
     * 
     * @var string
     */
    protected $path;
    
    /**
     * Use ssl
     * 
     * @var boolean
     */
    protected $ssl = false;
    
    /**
     * Check certificates
     * 
     * @var boolean
     */
    protected $validateCert = true;
    
    /**
     * Creates an HttpMessagePublishes which can send Gelf\Message Objects to 
     * a Graylog2 server via HTTP
     * 
     * @param string $host
     * @param int $port
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT, $path = self::DEFAULT_PATH) {
        if (!$host) {
            throw new InvalidArgumentException("\$host must not be empty");
        }
        
        if (!is_int($port) || $port < 1 || $port > 65535) {
            throw new InvalidArgumentException("\$port must be an int in the range 1-65535");
        }
        
        if (strlen($path) < 1 || $path[0] != '/') {
            throw new InvalidArgumentException("\$path must not be empty and start with a slash");
        }
        
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
    }
    

    /**
     * Publishes a Message, returns false if an error occured during write
     *
     * @throws UnexpectedValueException
     * @param Gelf\Message $message
     * @return boolean
     */
    public function publish(Message $message) {
        // prepare GELF message
        $message->setVersion(self::GRAYLOG2_PROTOCOL_VERSION);
        $jsonMessage = json_encode($message->toArray());
        
        // create and send http request
        $httpRequest = $this->prepareHttpRequest($jsonMessage);
        $socket = $this->getSocket();
        
        if (false === fwrite($socket, $httpRequest)) {
            throw new RuntimeException("fwrite failed");
        }
        
        fclose($socket);
        return true;
    }
    
    /**
     * Creates a valid HTTP-Request string
     * 
     * @param string $body
     * @return string
     */
    protected function prepareHttpRequest($body) {
        $request = "POST %s HTTP/1.1\r\n"
                 . "Host: %s\r\n"
                 . "Content-Type: application/json\r\n"
                 . "Content-Length: %d\r\n"
                 . "Connection: Close\r\n"
                 . "\r\n"
                 . "%s\r\n";
        
        return sprintf($request, $this->path, $this->host, strlen($body), $body);
    }
    
    /**
     * Returns an open socket to $this->host:$this->port
     * 
     * @throws RuntimeException
     * @return resource
     */
    protected function getSocket() {
        $socketDescriptor = sprintf("%s://%s:%d", $this->ssl ? 'ssl' : 'tcp', $this->host, $this->port);
        $context = stream_context_create();
        
        // add optional ssl context options
        if ($this->ssl && !$this->validateCert) {
            stream_context_set_option($context, "ssl", "allow_self_signed", true);
            stream_context_set_option($context, "ssl", "verify_peer", false);
        }
        
        // create socket
        $socket = stream_socket_client(
			$socketDescriptor, 
            $errno, $errstr, 
            ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, 
			$context
        );
        
        if (!$socket) {
            throw new RuntimeException("stream_socket_client on $socketDescriptor failed: $errstr ($errno)");
        }
        
        return $socket;
    }
    
    /**
     * Use an ssl-encrypted https connection
     * 
     * @param boolean $ssl
     */
    public function useSSL($ssl) {
        $this->ssl = (boolean) $ssl;
    }
    
    /**
     * Validate ssl certificates
     * 
     * @param boolean $validate
     */
    public function validateSSLCert($validate) {
        $this->validateCert = (boolean) $validate;
    }
    
    
}
