<?php

require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
  case 'archive':
    archive();
    break;
  case 'viewFrog':
    viewFrog();
    break;
  default:
    homepage();
}


function archive() {
  $results = array();
  $data = Frog::getList();
  $results['frogs'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Frog Pond";
  require( TEMPLATE_PATH . "/archive.php" );
}

function viewFrog() {
  if ( !isset($_GET["frogId"]) || !$_GET["frogId"] ) {
    homepage();
    return;
  }

  $results = array();
  $results['frog'] = Frog::getById( (int)$_GET["frogId"] );
  $results['pageTitle'] = $results['frog']->title;
  require( TEMPLATE_PATH . "/viewFrog.php" );
}

function homepage() {
  $results = array();
  $data = Frog::getList( HOMEPAGE_NUM_ARTICLES );
  $results['frogs'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "List of Frogs in Pond";
  require( TEMPLATE_PATH . "/homepage.php" );
}

?>