<?php
class Dbconfig
{
    protected $serverName;
    protected $userName;
    protected $passCode;
    protected $dbName;

    public function __construct()
    {
        $this -> serverName = 'localhost';
        $this -> userName = 'root';
        $this -> passCode = '';
        $this -> dbName = 'dtbutil';
    }
}
