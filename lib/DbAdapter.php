<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/2/16
 * Time: 4:48 PM
 */

namespace IronSourceAtom;

require 'DbHandler.php';

class DbAdapter
{
    const REPORTS_TABLE = "reports";
    const KEY_DATA = "data";
    const KEY_STREAM = "stream_name";
    const KEY_CREATED_AT = "created_at";
    const STREAMS_TABLE = "streams";
    const KEY_TOKEN = "token";
    private $db;

    public function __construct()
    {
        $this->db = new DbHandler();
    }

    public function addEvent($stream, $data)
    {


        $insertStmt = $this->db->prepare("INSERT INTO " . self::REPORTS_TABLE .
            " (" . self::KEY_STREAM . ", " . self::KEY_DATA . ", " . self::KEY_CREATED_AT . "
            ) VALUES (:stream, :data, :created_at)");
        $insertStmt->bindValue(':stream', $stream, SQLITE3_TEXT);
        $insertStmt->bindValue(':data', $data, SQLITE3_TEXT);
        $insertStmt->bindValue(':created_at', $this->milliseconds());
        $insertStmt->execute();

//        $countStreamsStmt = $this->db->prepare("SELECT COUNT(*) FROM ".self::STREAMS_TABLE. "WHERE ".self::KEY_STREAM . " = :stream");
//        $countStreamsStmt->bindValue(':stream', $stream);
//        $streamsCount = $countStreamsStmt->execute();

        $countEventsStmt = $this->db->prepare("SELECT COUNT(*) FROM " . self::REPORTS_TABLE . " WHERE " . self::KEY_STREAM . "= ?");
        $countEventsStmt->bindValue('s', $stream);
        $eventsCount = $countEventsStmt->execute();

//        if ($streamsCount == 0)
//        {
//            $this->addStream(streamData);
//        }

        return $eventsCount;

    }

    public function getEvents($stream, $limit)
    {
        $events = array();
        $event_ids = array();
        $stmt = $this->db->prepare("SELECT * FROM " . self::REPORTS_TABLE);
        $result = $stmt->execute();
        while ($row = $result->fetchArray()) {
            array_push($event_ids, $row[self::REPORTS_TABLE . '_id']);
            array_push($events, $row[self::KEY_DATA]);
        }
        $lastId = end($event_ids);
        $batch = new Batch($lastId, $events);
        var_dump($batch);

        return $batch;

    }

    /**
     * Remove events from records table that related to the given "table/destination"
     * and with an id that less than or equal to the "lastId"
     * @param stream
     * @param lastId
     * @return the number of rows affected
     */
    public function deleteEvents($stream, $lastId)
    {

        $deleteStmt = $this->db->prepare("DELETE FROM " . self::REPORTS_TABLE . " WHERE " . self::KEY_STREAM . "= :stream AND " . self::REPORTS_TABLE . "_id <= :event_id");
        $deleteStmt->bindParam(':stream', $stream);
        $deleteStmt->bindParam(':event_id', $lastId);
        $deleteStmt->execute();

    }


    public function create()
    {
        print("Creating the IronSourceAtom database");

        $reportQuery = 'CREATE TABLE IF NOT EXISTS ' . self::REPORTS_TABLE . ' (' . self::REPORTS_TABLE . '_id INTEGER PRIMARY KEY AUTOINCREMENT,' .
            self::KEY_DATA . ' STRING NOT NULL, ' . self::KEY_STREAM . ' STRING NOT NULL, ' . self::KEY_CREATED_AT . ' INTEGER NOT NULL);';
        $ret = $this->db->exec($reportQuery);


        $tableQuery = "CREATE TABLE IF NOT EXISTS " . self::STREAMS_TABLE . " (" . self::STREAMS_TABLE . "_id INTEGER PRIMARY KEY AUTOINCREMENT," .
            self::KEY_STREAM . " STRING NOT NULL UNIQUE, " . self::KEY_TOKEN . " STRING NOT NULL);";
        $ret = $this->db->exec($tableQuery);

        $indexQuery = "CREATE INDEX IF NOT EXISTS time_idx ON " . self::REPORTS_TABLE . " (" . self::KEY_CREATED_AT . ");";
        $ret = $this->db->exec($indexQuery);


    }

    function milliseconds()
    {
        $mt = explode(' ', microtime());
        return $mt[1] * 1000 + round($mt[0] * 1000);
    }
}


/**
 * Batch is just a syntactic-sugar way to store bulk of events
 * with its lastId to acknowledge them later
 */
class Batch
{
    /**
     * @var string
     */
    private $lastId;

    /**
     * @var string
     */
    private $events;

    public function __construct($lastId, $events)
    {
        $this->lastId = $lastId;
        $this->events = $events;
    }

    /**
     * @return string
     */
    public function getLastId()
    {
        return $this->lastId;
    }

    /**
     * @return string
     */
    public function getEvents()
    {
        return $this->events;
    }
}