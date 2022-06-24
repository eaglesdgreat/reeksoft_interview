<?php

/**
 * Class to handle articles
 */

class Frog
{

  // Properties

  /**
  * @var int The frog ID from the database
  */
  public $id = null;

  /**
  * @var string The species frog belong to
  */
  public $species = null;

  /**
  * @var string Color of the frog
  */
  public $color = null;

  /**
  * @var string Weight of the frog
  */
  public $weight_kg = null;

  /**
  * @var bool Is the frog poisonous
  */
  public $is_poisonous = null;

   /**
  * @var string Date created
  */
  public $created_at = null;

   /**
  * @var string Date updated
  */
  public $updated_at = null;


  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['created_at'] ) ) $this->created_at = (int) $data['created_at'];
    if ( isset( $data['updated_at'] ) ) $this->updated_at = (int) $data['updated_at'];
    if ( isset( $data['species'] ) ) $this->species = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['species'] );
    if ( isset( $data['color'] ) ) $this->color = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['color'] );
    if ( isset( $data['weight_kg'] ) ) $this->weight_kg = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['weight_kg'] );
    if ( isset( $data['is_poisonous'] ) ) $this->is_poisonous = $data['is_poisonous'];
  }


  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */

  public function storeFormValues ( $params ) {

    // Store all the parameters
    $this->__construct( $params );

    // Parse and store the created and updated date
    if ( isset($params['created_at']) ) {
      $created_at = explode ( '-', $params['created_at'] );

      if ( count($created_at) == 3 ) {
        list ( $y, $m, $d ) = $created_at;
        $this->created_at = mktime ( 0, 0, 0, $m, $d, $y );
      }
    }

    if ( isset($params['updated_at']) ) {
        $updated_at = explode ( '-', $params['updated_at'] );
  
        if ( count($updated_at) == 3 ) {
          list ( $y, $m, $d ) = $updated_at;
          $this->updated_at = mktime ( 0, 0, 0, $m, $d, $y );
        }
      }
  }


  /**
  * Returns a Frog object matching the given frog ID
  *
  * @param int The frog ID
  * @return Frog|null The frog object, or null if the record was not found or there was a problem
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
    $sql = "SELECT *, UNIX_TIMESTAMP(created_at) AS created_at FROM frogs WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Frog( $row );
  }


  /**
  * Returns all (or a range of) frog objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @return Array|null A two-element array : results => array, a list of Frog objects; totalRows => Total number of frogs
  */

  public static function getList( $numRows=1000000 ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(created_at) AS created_at FROM frogs
            ORDER BY id DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $frog = new Frog( $row );
      $list[] = $frog;
    }

    // Now get the total number of frogs that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts the current Frog object into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Frog object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Frog::insert(): Attempt to insert a Frog object that already has its ID property set (to $this->id).", E_USER_ERROR );

    
    // Insert the Frog
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO frogs ( species, color, weight_kg, is_poisonous, created_at ) VALUES ( :species, :color, :weight_kg, :is_poisonous, FROM_UNIXTIME(:created_at) )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":species", $this->species, PDO::PARAM_STR );
    $st->bindValue( ":color", $this->color, PDO::PARAM_STR );
    $st->bindValue( ":weight_kg", $this->weight_kg, PDO::PARAM_STR );
    $st->bindValue( ":is_poisonous", $this->is_poisonous, PDO::PARAM_STR );
    $st->bindValue( ":created_at", $this->created_at, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Updates the current Frog object in the database.
  */

  public function update() {

    // Does the Frog object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Frog::update(): Attempt to update an Frog object that does not have its ID property set.", E_USER_ERROR );
   
    // Update the Frog
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE frogs SET species=:species, color=:color, weight_kg=:weight_kg, is_poisonous=:is_poisonous,created_at=FROM_UNIXTIME(:created_at), updated_at=FROM_UNIXTIME(:updated_at) WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":species", $this->species, PDO::PARAM_STR );
    $st->bindValue( ":color", $this->color, PDO::PARAM_STR );
    $st->bindValue( ":weight_kg", $this->weight_kg, PDO::PARAM_STR );
    $st->bindValue( ":is_poisonous", $this->is_poisonous, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->bindValue( ":created_at", $this->created_at, PDO::PARAM_INT );
    $st->bindValue( ":updated_at", $this->updated_at, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Deletes the current Frog object from the database.
  */

  public function delete() {

    // Does the Frog object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Frog::delete(): Attempt to delete an Frog object that does not have its ID property set.", E_USER_ERROR );

    // Delete the Frog
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM frogs WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>