<?php
/**
 * @author bigbigant
 */
namespace Comos\Qpm\Supervision;

use Comos\Qpm\Supervision\Supervisor;
use Comos\Qpm\Supervision\StopSignal;

class TaskFactoryKeeperTest extends \PHPUnit_Framework_TestCase
{

    protected $logFile;

    protected function setUp()
    {
        parent::setUp();
        $this->logFile = __FILE__ . '.log';
        @\unlink($this->logFile);
    }

    protected function tearDown()
    {
        @\unlink($this->logFile);
    }

    protected $_count = 0;

    public function mockFetchTask()
    {
        $count = $this->_count ++;
        if ($count == 10) {
            throw new StopSignal();
        }
        if (0 == $count % 3) {
            return null;
        }
        return new TaskFactoryKeeperTest_Task($count, $this->logFile);
    }

    public function testRun()
    {
        Supervisor::taskFactoryMode(array('quantity' => 3, 'factoryMethod' => array($this, 'mockFetchTask')))
            ->start();
        $content = \file_get_contents($this->logFile);
        $arr = \str_split($content);
        \sort($arr);
        $this->assertEquals("124578", \join('', $arr));
    }
}

class TaskFactoryKeeperTest_Task implements \Comos\Qpm\Process\Runnable
{

    private $id;

    private $logFile;

    public function __construct($id, $logFile)
    {
        $this->id = $id;
        $this->logFile = $logFile;
    }

    public function run()
    {
       \file_put_contents($this->logFile, $this->id, \FILE_APPEND);
    }
}