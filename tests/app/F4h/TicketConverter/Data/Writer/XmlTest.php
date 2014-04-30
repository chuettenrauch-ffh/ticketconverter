<?php

require_once dirname(__FILE__) . '/../../../../../../app/F4h/TicketConverter/Data/Writer/Xml.php';
require_once dirname(__FILE__) . '/../../../../../../lib/vfsStream/vfsStream.php';
/**
 * Test class for F4h_TicketConverter_Data_Writer_Xml.
 * Generated by PHPUnit on 2012-01-10 at 11:03:17.
 */
class F4h_TicketConverter_Data_Writer_XmlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var F4h_TicketConverter_Data_Writer_Xml
     */
    protected $object;
    protected $queue;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new F4h_TicketConverter_Data_Writer_Xml;
        $this->queue = $this->getMock('F4h_TicketConverter_Model_Queue');
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers F4h_TicketConverter_Data_Writer_Xml::write
     */
    public function testWriteWithOneId()
    {
        $this->queue->enqueue('4114');
        $this->object->write($this->queue);
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers {className}::{origMethodName}
     * @todo Implement testAdd().
     */
    public function testAdd()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers {className}::{origMethodName}
     * @todo Implement testBuildFilenamesXml().
     */
    public function testBuildFilenamesXml()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>
