<?php
if( !function_exists('database_backup_get_connection') ) {
  function database_backup_get_connection()
  {
    try {
      $db = Engine_Db_Table::getDefaultAdapter();
      $export = Engine_Db_Export::factory($db);
      return $export->getAdapter()->getConnection();
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_connect') ) {
  function mysql_connect($server, $username, $password, $new_link = null, $client_flags = null)
  {
    try {

      return mysqli_connect($server, $username, $password);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_error') ) {
  function mysql_error()
  {
    try {

      $connection = database_backup_get_connection();
      return mysqli_error($connection);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_select_db') ) {
  function mysql_select_db($dbname, $link)
  {
    try {

      return mysqli_select_db($link, $dbname);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_query') ) {
  function mysql_query($query)
  {
    try {

      $connection = database_backup_get_connection();
      return mysqli_query($connection, $query);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_fetch_object') ) {
  function mysql_fetch_object($result)
  {
    try {

      return mysqli_fetch_object($result);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_fetch_array') ) {
  function mysql_fetch_array($result)
  {
    try {

      return mysqli_fetch_array($result);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_insert_id') ) {
  function mysql_insert_id()
  {
    try {

      $connection = database_backup_get_connection();
      return mysqli_insert_id($connection);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
if( !function_exists('mysql_fetch_assoc') ) {
  function mysql_fetch_assoc($result)
  {
    try {

      return mysqli_fetch_assoc($result);
    } catch( Exception $e ) {

      die("error occured : " . $e);
    }
  }

}
?>