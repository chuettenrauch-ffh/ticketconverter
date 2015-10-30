<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter-tests
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Claudia Hüttenrauch <claudia.hüttenrauch@fashionforhome.de>
 * @author Tino Stöckel <tino.stoeckel@fashionforhome.de>
 *
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 30.10.2015
 * Time: 01:30
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . '/../../../../../app/F4h/TicketConverter/Data/Mapper.php';

/**
 * Test class for F4h_TicketConverter_Data_Mapper.
 * Generated by PHPUnit on 2012-01-09 at 15:34:35.
 */
class F4h_TicketConverter_Data_MapperTest extends PHPUnit_Framework_TestCase
{

    protected $testIds = array('4114', '4112');

    /**
     * @var F4h_TicketConverter_Data_Mapper
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $queue = $this->getMock('F4h_TicketConverter_Model_Queue');
        foreach ($this->testIds as $id) {
            $queue->enqueue($id);
        }
        $this->object = new F4h_TicketConverter_Data_Mapper($queue);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers F4h_TicketConverter_Data_Mapper::__construct
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers F4h_TicketConverter_Data_Mapper::map
     */
    public function testMap()
    {
        $returnValue = $this->object->map();
        $this->assertInstanceOf('F4h_TicketConverter_Model_Queue', $returnValue);
        
        foreach ($returnValue as $value)
        {
            $this->assertInstanceOf('F4h_TicketConverter_Model_Ticket', $value);
        }
    }

}

?>
