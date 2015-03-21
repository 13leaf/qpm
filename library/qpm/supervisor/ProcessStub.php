<?php
/**
 * @author bigbigant
 * @license GPL-3
 */
namespace qpm\supervisor;

use \qpm\process\Process;

class ProcessStub
{

    /**
     *
     * @var Process
     */
    private $process;

    /**
     *
     * @var Config
     */
    private $config;

    /**
     *
     * @var float
     */
    private $startTime;

    /**
     *
     * @var mix
     */
    private $groupId;

    /**
     *
     * @param Process $process            
     * @param Config $config            
     * @param float $startTime            
     */
    public function __construct(Process $process, Config $config, $groupId = null, $startTime = null)
    {
        $this->process = $process;
        $this->config = $config;
        $this->startTime = is_null($startTime) ? \microtime(true) : $startTime;
        $this->groupId = $groupId;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     *
     * @return boolean
     */
    public function isTimeout()
    {
        if (! $this->config->isTimeoutEnabled()) {
            return false;
        }
        
        $duration = \microtime(true) - $this->getStartTime();
        return $duration > $this->config->getTimeout();
    }

    /**
     *
     * @return \qpm\process\Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     *
     * @return \qpm\supervisor\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
}