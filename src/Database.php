<?php

class Database
{
  private ?PDO $conn = null;

  private  $host = 'localhost';
  private  $name  = 'api_db';
  private  $user = 'root';
  private  $password  = '123456';

  public function getConnection(): PDO
  {
    if ($this->conn === null) {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
    
        $this->conn = new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
    }
   
    return $this->conn;
  }

}