<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/2/16
 * Time: 4:48 PM
 */

namespace IronSourceAtom;


class DbAdapter
{
    const REPORTS_TABLE = "reports";
    const KEY_DATA = "data";
    const KEY_STREAM = "stream_name";
    const KEY_CREATED_AT = "created_at";
    const STREAMS_TABLE = "streams";
    const KEY_AUTH_KEY = "auth_key";
    const KEY_BYTE_SIZE = "byte_size";
    private $db;

    public function __construct()
    {
        $this->db = new DbHandler();
    }

    public function addEvent($stream, $data, $authKey)
    {


        $insertStmt = $this->db->prepare("INSERT INTO " . self::REPORTS_TABLE .
            " (" . self::KEY_STREAM . ", " . self::KEY_DATA . ", " . self::KEY_CREATED_AT . "
            ) VALUES (:stream, :data, :created_at)");
        $insertStmt->bindValue(':stream', $stream, SQLITE3_TEXT);
        $insertStmt->bindValue(':data', $data, SQLITE3_TEXT);
        $insertStmt->bindValue(':created_at', $this->milliseconds());
        $insertStmt->execute();

        $streamsCount = $this->countStreams($stream);

        if ($streamsCount == 0) {
            $this->addStream($stream, $authKey, 0);
        }

        $byte_size = $this->getByteSize($stream);
        $byte_size += mb_strlen($data, '8bit');
        $this->updateByteSize($stream, $byte_size);

    }

    public function getEvents($stream, $limit)
    {
        $events = array();
        $event_ids = array();
        $stmt = $this->db->prepare("SELECT * FROM " . self::REPORTS_TABLE
            . " WHERE " . self::KEY_STREAM . " = :stream ORDER BY " . self::KEY_CREATED_AT . " ASC"
            . " LIMIT :limit");
        $stmt->bindParam(':stream', $stream);
        $stmt->bindParam(':limit', $limit);
        $result = $stmt->execute();
        $byteSize = 0;
        while ($row = $result->fetchArray()) {
            array_push($event_ids, $row[self::REPORTS_TABLE . '_id']);
            array_push($events, $row[self::KEY_DATA]);
            $byteSize += mb_strlen($row[self::KEY_DATA], '8bit');
        }
        $lastId = end($event_ids);
        $batch = new Batch($lastId, $byteSize, $events);
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
            self::KEY_STREAM . " STRING NOT NULL UNIQUE, " . self::KEY_AUTH_KEY . " STRING NOT NULL, " . self::KEY_BYTE_SIZE . " INTEGER);";
        $ret = $this->db->exec($tableQuery);

        $indexQuery = "CREATE INDEX IF NOT EXISTS time_idx ON " . self::REPORTS_TABLE . " (" . self::KEY_CREATED_AT . ");";
        $ret = $this->db->exec($indexQuery);


    }

    public function milliseconds()
    {
        $mt = explode(' ', microtime());
        return $mt[1] * 1000 + round($mt[0] * 1000);
    }

    public function countEvents($stream)
    {
        $countEventsStmt = $this->db->prepare("SELECT COUNT(*) AS NUM FROM " . self::REPORTS_TABLE . " WHERE " . self::KEY_STREAM . "= :stream");
        $countEventsStmt->bindParam(':stream', $stream);
        $eventsCount = $countEventsStmt->execute();
        $row = $eventsCount->fetchArray();
        return $row['NUM'];
    }

    private function addStream($stream, $authKey, $byteSize)
    {
        $insertStmt = $this->db->prepare("INSERT INTO " . self::STREAMS_TABLE .
            " (" . self::KEY_STREAM . ", " . self::KEY_AUTH_KEY . ", " . self::KEY_BYTE_SIZE . "
            ) VALUES (:stream, :auth, :byte_size)");
        $insertStmt->bindValue(':stream', $stream, SQLITE3_TEXT);
        $insertStmt->bindValue(':auth', $authKey, SQLITE3_TEXT);
        $insertStmt->bindValue(':byte_size', $byteSize, SQLITE3_INTEGER);
        $insertStmt->execute();
    }

    /**
     * @param $stream
     * @return integer
     */
    public function getByteSize($stream)
    {
        $getSizeStmt = $this->db->prepare("SELECT " . self::KEY_BYTE_SIZE . " FROM " . self::STREAMS_TABLE . " WHERE " . self::KEY_STREAM . " = :stream");
        $getSizeStmt->bindValue(':stream', $stream);
        $raw = $getSizeStmt->execute();
        $result = $raw->fetchArray();
        $byte_size = $result[self::KEY_BYTE_SIZE];
        Logger::log('Byte size of events in stream ' . $stream . " = " . $byte_size, true);
        return $byte_size;
    }

    /**
     * @param $stream
     * @param $byte_size
     */
    public function updateByteSize($stream, $byte_size)
    {
        $updateStmt = $this->db->prepare("UPDATE " . self::STREAMS_TABLE . " SET " . self::KEY_BYTE_SIZE . " = :byte_size WHERE " . self::KEY_STREAM . " = :stream");
        $updateStmt->bindValue(':byte_size', $byte_size);
        $updateStmt->bindValue(':stream', $stream);
        $updateStmt->execute();
    }

    /**
     * @param $stream
     * @return mixed
     */
    private function countStreams($stream)
    {
        $countStreamsStmt = $this->db->prepare("SELECT COUNT(*)  AS NUM FROM " . self::STREAMS_TABLE . " WHERE " . self::KEY_STREAM . " = :stream");
        $countStreamsStmt->bindValue(':stream', $stream);
        $raw = $countStreamsStmt->execute();
        $result = $raw->fetchArray();
        $streamsCount = $result['NUM'];
        Logger::log('Numbers of streams ' . $stream . " in table " . self::STREAMS_TABLE . " is " . $streamsCount, false);
        return $streamsCount;
    }

    /**
     * @param $stream
     * @return integer
     */
    public function getOldestCreationTime($stream){
        $timeStmt = $this->db->prepare("SELECT MIN(". self::KEY_CREATED_AT.")  AS start_time FROM " . self::REPORTS_TABLE . " WHERE " . self::KEY_STREAM . " = :stream");
        $timeStmt->bindValue(':stream', $stream);
        $raw = $timeStmt->execute();
        $result = $raw->fetchArray();
        return $result['start_time'];
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
     * @var integer
     */
    private $byteSize;

    /**
     * @var string
     */
    private $events;

    public function __construct($lastId, $byteSize, $events)
    {
        $this->lastId = $lastId;
        $this->byteSize = $byteSize;
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
     * @return int
     */
    public function getByteSize()
    {
        return $this->byteSize;
    }

    /**
     * @return string
     */
    public function getEvents()
    {
        return $this->events;
    }
}

class DbHandler extends \SQLite3
{
    function __construct()
    {
        $this->open('ironsourceatom.db');
    }

}