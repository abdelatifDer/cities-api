<?php

/**
 * CLass connection with the database
 */
class Database
{

  /**
   * the host name
   * @var string
   */
  private $DB_HOST = 'localhost';

  /**
   * the database name
   * @var string
   */
  private $DB_NAME = 'cities-api';

  /**
   * the user name
   * @var string
   */
  private $DB_USER = 'root';

  /**
   * the user password
   * @var string
   */
  private $DB_PASSWORD = '';


  /**
   * the database handler
   * @var object
   */
  private $dbh;

  /**
   * the statement
   * @var object
   */
  private $stmt;

  /**
   * the error
   * @var string
   */
  private $err;

  public function __construct()
  {

    try {

      $DSN = 'mysql:host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . '';
      $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_EMULATE_PREPARES => false
      ];


      $this->dbh = new \PDO($DSN, $this->DB_USER, $this->DB_PASSWORD, $options);

      // catch any error
    } catch (\PDOException $e) {
      $this->err = $e->getMessage();
      echo $this->err;
    }
  }

  /**
   * function for query handling
   * @param string $query - the query order
   */
  public function query($query)
  {
    $this->stmt = $this->dbh->prepare($query);
  }

  /**
   * bind any value to the statement
   * @param string $key - assing the key
   * @param string $value - the key's value
   * @param        $param - assing the specific parameter
   */
  public function bind($key, $value, $param = \PDO::PARAM_STR)
  {

    // check for value's type
    // then assign it to PDO
    switch (true) {
      case is_bool($value):
        $param = \PDO::PARAM_BOOL;
        break;
      case is_null($value):
        $param = \PDO::PARAM_NULL;
        break;
      case is_int($value):
        $param = \PDO::PARAM_INT;
        break;
      default:
        $param = \PDO::PARAM_STR;
    }

    $this->stmt->bindValue($key, $value, $param);
  }


  /**
   * method to execute the statement
   */
  public function execute()
  {
    $this->stmt->execute();
  }

  /**
   * fetch all resulst in an array (multiple results)
   */
  public function fetchResults()
  {
    $this->execute();
    return $this->stmt->fetchAll();
  }

  /**
   * fetch a single result
   */
  public function fetchResult()
  {
    $this->execute();
    return $this->stmt->fetch();
  }

  /**
   * count the number of the table's rows
   * @return int the rows number
   */
  public function rowCount()
  {

    return $this->stmt->rowCount();
  }
}
