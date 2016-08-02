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
    const REPORTS_TABLE = "";
    const KEY_DATA = "";
    const KEY_TABLE = "";
    const KEY_CREATED_AT = "";
    const TABLES_TABLE = "";
    const KEY_TOKEN = "";
    private $db;

    public function __construct()
    {
        $this->db = new DbHandler();
    }

    public function create()
    {
        print("Creating the IronSourceAtom database");

        $reportQuery = 'CREATE TABLE ' . self::REPORTS_TABLE . ' (' . self::REPORTS_TABLE . '_id INTEGER PRIMARY KEY AUTOINCREMENT,' .
            self::KEY_DATA . ' STRING NOT NULL, ' . self::KEY_TABLE . ' STRING NOT NULL, ' . self::KEY_CREATED_AT . ' INTEGER NOT NULL);';
        $ret = $this->db->exec($reportQuery);


        $tableQuery = "CREATE TABLE " . self::TABLES_TABLE . " (" . self::TABLES_TABLE . "_id INTEGER PRIMARY KEY AUTOINCREMENT," .
            self::KEY_TABLE . " STRING NOT NULL UNIQUE, " . self::KEY_TOKEN . " STRING NOT NULL);";
        $ret = $this->db->exec($reportQuery);

        $indexQuery = "CREATE INDEX IF NOT EXISTS time_idx ON " . self::REPORTS_TABLE . " (" . self::KEY_CREATED_AT . ");";
        $ret = $this->db->exec($reportQuery);

    }
}