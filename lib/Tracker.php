<?php
/**
 */

namespace IronSourceAtom;


require 'Atom.php';

class Tracker{
    private $bulkSizeByte = 65536;
    private $bulkSize = 4;
    private $taskWorkersCount = 20;
    private $taskPoolSize = 10000;
    private $flushInterval = 10;
    private $url;
    private $authKey = '';
    private $streams;
    private $atom;
    private $flush_now;
    private $eventPool;

    /**
     * Tracker constructor.
     * @param string $url
     */
    public function __construct($url="http://track.atom-data.io/"){
        $this->url = $url;
    }

    /**
     * @param int $bulkSizeByte
     */
    public function setBulkSizeByte($bulkSizeByte){
        $this->bulkSizeByte = $bulkSizeByte;
    }

    /**
     * @param int $bulkSize
     */
    public function setBulkSize($bulkSize){
        $this->bulkSize = $bulkSize;
    }

    /**
     * @param int $taskWorkersCount
     */
    public function setTaskWorkersCount($taskWorkersCount){
        $this->taskWorkersCount = $taskWorkersCount;
    }

    /**
     * @param int $taskPoolSize
     */
    public function setTaskPoolSize($taskPoolSize){
        $this->taskPoolSize = $taskPoolSize;
    }

    /**
     * @param int $flushInterval
     */
    public function setFlushInterval($flushInterval){
        $this->flushInterval = $flushInterval;
    }

    /**
     * @param string $url
     */
    public function setUrl($url){
        $this->url = $url;
    }

    /**
     * @param string $authKey
     */
    public function setAuthKey($authKey){
        $this->authKey = $authKey;
    }

    /**
     * @param $data
     * @param $stream
     */
    public function track($data, $stream){

    }

    /**
     *
     */
    public function flush(){
        $this->flush_now = true;
    }

    private function eventWorker(){

    }

    private function flushData($stream, $data){

    }
}

$tracker = new Tracker();
var_dump($tracker);