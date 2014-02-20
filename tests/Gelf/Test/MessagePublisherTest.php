<?php

namespace Gelf\Test;

use Gelf\Message;
use Gelf\MessagePublisher;

class MessagePublisherTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Message */
    private $message;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $publisher;

    protected function setUp()
    {
        $this->message = new Message();
        $this->setValuesToMessage($this->message);

        $this->publisher = $this->getMockBuilder('\Gelf\MessagePublisher')
          ->setMethods(array('writeMessageChunkToSocket', 'writeMessageToSocket', 'getSocketConnection'))
          ->setConstructorArgs(array('localhost', 12201, 1420))
          ->getMock();

        $this->publisher->expects($this->any())->method('getSocketConnection')->will($this->returnValue('a_socket'));
    }

    public function testExtendsInterface()
    {
        $this->assertInstanceOf('\Gelf\IMessagePublisher', $this->publisher);
    }

    public function testPublishSingleChunk()
    {
        $this->publisher->expects($this->once())->method('writeMessageToSocket')->with('a_socket')->will(
          $this->returnValue(true)
        );

        $this->assertEquals(true, $this->publisher->publish($this->message));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testPublishMessageExceptionNoShortMessage()
    {
        $this->message->setShortMessage('');

        $this->publisher->publish($this->message);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testPublishMessageExceptionNoHost()
    {
        $this->message->setHost('');

        $this->publisher->publish($this->message);
    }

    public function testPublishSingleChunkFailure()
    {
        $this->publisher->expects($this->once())->method('writeMessageToSocket')->with('a_socket')->will(
          $this->returnValue(false)
        );

        $this->assertEquals(false, $this->publisher->publish($this->message));
    }

    public function testPublishMultipleChunks()
    {
        // Need a message that will be splitted in 2 chunks.
        $this->message->setFullMessage(str_repeat('foobarfoobarfoobarfoobar,', 40000));

        $this->publisher->expects($this->exactly(2))->method('writeMessageChunkToSocket')->with('a_socket')->will(
          $this->returnValue(true)
        );

        $this->assertEquals(true, $this->publisher->publish($this->message));
    }

    public function testPublishMultipleChunksFailure()
    {
        // Need a message that will be splitted in 2 chunks.
        $this->message->setFullMessage(str_repeat('foobarfoobarfoobarfoobar,', 40000));

        $this->publisher->expects($this->once())->method('writeMessageChunkToSocket')->with('a_socket')->will(
          $this->returnValue(false)
        );

        $this->assertEquals(false, $this->publisher->publish($this->message));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidHostname()
    {
        new MessagePublisher('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPort()
    {
        new MessagePublisher('localhost', 'abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidChunkSize()
    {
        new MessagePublisher('localhost', 42, 'def');
    }

    /**
     * @requires function hex2bin
     */
    public function testInternalPrependChunkInformation()
    {
        $method = new \ReflectionMethod('\Gelf\MessagePublisher', 'prependChunkInformation');
        $method->setAccessible(true);

        $this->assertEquals(hex2bin('1e0fe48e13207341b6bf002a616263'), $method->invoke($this->publisher, 1337, 'abc', 0, 42));
        $this->assertEquals(hex2bin('1e0fe48e13207341b6bf012a616263'), $method->invoke($this->publisher, 1337, 'abc', 1, 42));
    }

    protected function setValuesToMessage(Message $message)
    {
        $message->setFacility('facility');
        $message->setFile('foo.php');
        $message->setFullMessage('Full Message');
        $message->setHost('example.com');
        $message->setLevel(3);
        $message->setLine(42);
        $message->setShortMessage('Short Message');
        $message->setTimestamp(123456789);
        $message->setVersion('1.2.3');
    }
}
 