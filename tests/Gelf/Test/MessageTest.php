<?php


namespace Gelf\Test;


use Gelf\Message;
use Gelf\MessagePublisher;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Message */
    private $message;

    protected function setUp()
    {
        $this->message = new Message();
    }

    public function testDefaults()
    {
        $this->assertEquals(null, $this->message->getFacility());
        $this->assertEquals(null, $this->message->getFile());
        $this->assertEquals(null, $this->message->getFullMessage());
        $this->assertEquals(null, $this->message->getHost());
        $this->assertEquals(null, $this->message->getLevel());
        $this->assertEquals(null, $this->message->getLine());
        $this->assertEquals(null, $this->message->getShortMessage());
        $this->assertEquals(null, $this->message->getTimestamp());
        $this->assertEquals(1.1, $this->message->getVersion());
    }

    public function testSetAndGet()
    {
        $this->setValuesToMessage($this->message);

        $this->assertEquals('facility', $this->message->getFacility());
        $this->assertEquals('foo.php', $this->message->getFile());
        $this->assertEquals('Full Message', $this->message->getFullMessage());
        $this->assertEquals('example.com', $this->message->getHost());
        $this->assertEquals(3, $this->message->getLevel());
        $this->assertEquals(42, $this->message->getLine());
        $this->assertEquals('Short Message', $this->message->getShortMessage());
        $this->assertEquals(123456789, $this->message->getTimestamp());
        $this->assertEquals('1.2.3', $this->message->getVersion());

    }

    public function testToArray()
    {
        $this->setValuesToMessage($this->message);

        $this->message->setAdditional('foo', 'bar');

        $this->assertEquals(
          array(
            'version' => '1.2.3',
            'timestamp' => 123456789,
            'short_message' => 'Short Message',
            'full_message' => 'Full Message',
            'host' => 'example.com',
            'level' => 3,
            '_facility' => 'facility',
            '_file' => 'foo.php',
            '_line' => 42,
            '_foo' => 'bar',
          ),
          $this->message->toArray()
        );
    }

    public function testAdditionals()
    {
        // Check default return value
        $this->assertEquals(null, $this->message->getAdditional('does_not_exist'));

        $this->message->setAdditional('foo', 'bar');
        $this->assertEquals('bar', $this->message->getAdditional('foo'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAdditional()
    {
        $this->message->setAdditional('id', 'baz');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAdditionalWithTrimming()
    {
        $this->message->setAdditional(' id ', 'baz');
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
 