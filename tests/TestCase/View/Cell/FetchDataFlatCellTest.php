<?php
namespace Comments\Test\TestCase\View\Cell;

use Cake\TestSuite\TestCase;
use Comments\View\Cell\FetchDataFlatCell;

/**
 * Comments\View\Cell\FetchDataFlatCell Test Case
 */
class FetchDataFlatCellTest extends TestCase
{

    /**
     * Request mock
     *
     * @var \Cake\Network\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public $request;

    /**
     * Response mock
     *
     * @var \Cake\Network\Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public $response;

    /**
     * Test subject
     *
     * @var \Comments\View\Cell\FetchDataFlatCell
     */
    public $FetchDataFlat;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMockBuilder('Cake\Network\Request')->getMock();
        $this->response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $this->FetchDataFlat = new FetchDataFlatCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FetchDataFlat);

        parent::tearDown();
    }

    /**
     * Test display method
     *
     * @return void
     */
    public function testDisplay()
    {

    }
}
