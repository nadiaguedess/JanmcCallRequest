<?php
$current_dir = dirname(__DIR__, 2);
include 'Dbconfig.php';

class Mysql extends Dbconfig
{
    public $connectionString;
    public $dataSet;
    private $sqlQuery;

    protected $databaseName;
    protected $hostName;
    protected $userName;
    protected $passCode;
    

    public function __construct()
    {
        $this -> connectionString = null;
        $this -> sqlQuery = null;
        $this -> dataSet = null;

        $dbPara = new Dbconfig();
        $this -> databaseName =  $dbPara -> dbName ;
        $this -> hostName = 	$dbPara -> serverName ;
        $this -> userName = 	$dbPara -> userName ;
        $this -> passCode = 	$dbPara -> passCode ;
        $dbPara = null;
    }

    public function dbConnect()
    {
        $this -> connectionString = mysqli_connect($this -> serverName, $this -> userName, $this -> passCode);
        mysqli_select_db($this -> connectionString, $this -> databaseName);
        return $this -> connectionString;
    }
    
    public function dbDisconnect()
    {
        $this -> connectionString = null;
        $this -> sqlQuery = null;
        $this -> dataSet = null;
        $this -> databaseName = null;
        $this -> hostName = null;
        $this -> userName = null;
        $this -> passCode = null;
    }

    public function timeZoneSet()
    {
        $offset="-03:00";
        $this -> sqlQuery = "SET time_zone='".$offset."'; ";
        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);
    }

    public function selectAll($tableName)
    {
        $this -> timeZoneSet();
        $this -> sqlQuery = 'SELECT * FROM '.$this -> databaseName.'.'.$tableName;
        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);
        return $this -> dataSet;
    }

    public function selectWhere($tableName, $rowName, $operator, $value, $valueType)
    {
        $this -> timeZoneSet();
        $this -> sqlQuery = 'SELECT * FROM '.$tableName.' WHERE '.$rowName.' '.$operator.' ';
        if ($valueType == 'int') {
            $this -> sqlQuery .= $value;
        } elseif ($valueType == 'char') {
            $this -> sqlQuery .= "'".$value."'";
        }
        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);
        $this -> sqlQuery = null;
        return $this -> dataSet;
        #return $this -> sqlQuery;
    }

    public function insertInto($tableName, $values)
    {
        $this -> timeZoneSet();
        $i = null;

        $this -> sqlQuery = 'INSERT INTO '.$tableName.' VALUES (';
        $i = 0;
        while ($values[$i]["val"] != null && $values[$i]["type"] != null) {
            if ($values[$i]["type"] == "char") {
                $this -> sqlQuery .= "'";
                $this -> sqlQuery .= $values[$i]["val"];
                $this -> sqlQuery .= "'";
            } elseif ($values[$i]["type"] == 'int') {
                $this -> sqlQuery .= $values[$i]["val"];
            }
            $i++;
            if ($values[$i]["val"] != null) {
                $this -> sqlQuery .= ',';
            }
        }
        $this -> sqlQuery .= ')';
        #echo $this -> sqlQuery;
        mysqli_query($this ->connectionString, $this -> sqlQuery);
        return $this -> sqlQuery;
        #$this -> sqlQuery = NULL;
    }

    public function selectFreeRun($query)
    {
        $this -> timeZoneSet();
        $this -> dataSet = mysqli_query($this -> connectionString, $query);
        return $this -> dataSet;
    }

    public function freeRun($query)
    {
        $this -> timeZoneSet();
        return mysqli_query($this -> connectionString, $query);
    }
}
