<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">

  

	<title>Number guessing game</title>
</head>
<body>
  <?php 
    // Connect to the database:
    $connection = db_connect(); 

    // Set the encoding...
    mysqli_set_charset($connection, 'utf8');
  ?>
  
<!-- Div for slider graphic that displays numbers picked -->
<div id="stage">
  <div id="scale"></div>
  <div id="arrow"></div>
</div>
<br>
<p id="output">I am thinking of a number between 0 and 1000.</p>

<!-- Keyword autofocus used so cursor automatically appears in input box-->
<input id="input" type="text" placeholder="Enter your guess…" autofocus>  

<button>Guess</button>
  

	
<!-- Main Javascript file -->
<script src="js/numberGuess.js"></script>
  
<?php
  // Display the initial leaderboard:
  display_scores();
  
/**
 * Database functions for a MySQL with PHP
 * Christopher Adams 12/20/2016 
 */
  
function display_scores(){
  // Create a query for all scores:
  $result3 = db_query("SELECT * FROM `highscore` ORDER BY `score`");

  // Store The query of all scores in an array:
  if($result3 === false){
  }else{
    $rows2 = array();
    while($row2 = mysqli_fetch_assoc($result3)){
      $rows2[] = $row2;
    }
  }

  // Get the total size of the array:
  $size = count($rows2);
  
  echo "<br><h2>Leaderboard</h2>";
  // Loop through and display array:
  for($i=0; $i < $size; $i++){
    echo "Initials: ";
    print_r($rows2[$i]['user_initials']);
    echo "<br>";
    echo "Score: ";
    print_r($rows2[$i]['score']);
    echo "<br>";
    echo "Date/Time: ";
    print_r($rows2[$i]['date']);
    echo "<br><br>";
  }

}
 
/**
 * Connect to the database
 * 
 * @return bool false on failure / mysqli MySQLi object instance on success
 */
function db_connect() {
    // Define connection as a static variable, to avoid connecting more than once 
    static $connection;

    // Try and connect to the database, if a connection has not been established yet
    if(!isset($connection)) {
		// Load configuration as an array. Use the actual location of your configuration file
		// Put the configuration file outside of the document root
		$config = parse_ini_file('config.ini'); 
        $connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
    }

    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
        echo "error connecting";
    }
    else{
        echo '<script>console.log("Connection successful")</script>';
    }
    return $connection;
}

/**
 * Query the database
 *
 * @param $query The query string
 * @return mixed The result of the mysqli::query() function
 */
function db_query($query) {
    // Connect to the database
    $connection = db_connect();

    // Query the database
    $result = mysqli_query($connection,$query);

    return $result;
}

/**
 * Fetch rows from the database (SELECT query)
 *
 * @param $query The query string
 * @return bool False on failure / array Database rows on success
 */
function db_select($query) {
    $rows = array();
    $result = db_query($query);

    // If query failed, return `false`
    if($result === false) {
        return false;
    }

    // If query was successful, retrieve all the rows into an array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Fetch the last error from the database
 * 
 * @return string Database error message
 */
function db_error() {
    $connection = db_connect();
    return mysqli_error($connection);
}

/**
 * Quote and escape value for use in a database query
 *
 * @param string $value The value to be quoted and escaped
 * @return string The quoted and escaped string
 */
function db_quote($value) {
    $connection = db_connect();
    return "'" . mysqli_real_escape_string($connection,$value) . "'";
}

?>
</body>
</html>