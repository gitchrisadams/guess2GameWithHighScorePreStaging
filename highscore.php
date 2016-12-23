<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">

  

	<title>Number guessing game</title>
</head>
<body>
<?php 
    $quessesMade = "";
    $correctGuess = "";
    $initials = "";
  
    // Connect to the database:
    $connection = db_connect(); 

    // Set the encoding...
    mysqli_set_charset($connection, 'utf8');
  
  // Get the number of guesses Made and the correct answer from the URL
  // and store it in variables:
  if(isset($_GET['guessesMade']) && isset($_GET['playerGuess'])){
      $guessesMade = $_GET['guessesMade'];
      $correctGuess = $_GET['playerGuess'];
      $initials = db_quote($_GET['initials']);
    
      // Output the guesses made and correct answer to the screen:
  echo "<h1>Correct Answer: " . $correctGuess . "</h1>";
  
  
  echo "<h2>Total guesses: ". $guessesMade . "</h2>";
  }

    

  
  // Create a query for the highest score:
  $result = db_query("SELECT MIN(`score`) FROM `highscore`");
  
  // Store The query in an array:
  if($result === false){
  }else{
    $rows = array();
    while($row = mysqli_fetch_assoc($result)){
      $rows[] = $row;
    }
  }

  
  // Select the score in score and store in rows array:
  $rows = db_select("SELECT MIN(`score`) FROM `highscore`");
  if($rows === false){
    $error = db_error();
  }
  
  // Save the highest previous score:
  $highestPreviousScore = ($rows[0]["MIN(`score`)"]);
  
  
  if(isset($_GET['guessesMade']) && isset($_GET['playerGuess'])){
    // Cast highest previous score to an int:
    $highestPreviousScore *= 1;

    // Cast the current player's score to an int:
    $guessesMade *= 1;

      if($guessesMade < $highestPreviousScore){
        
        // Insert highscore into the database:
        $result2 = db_query("INSERT INTO `highscore` (`user_initials`,`score`, `date`) VALUES ($initials, $guessesMade, now())"); 
        echo "<br><h2>You beat the best score! Congratulations!</h2>";
        echo "<a href = 'http://guess3.christopheradams.com'>Play again?</a>";
        
        // Call function to display scores:
        display_scores();
        
     }elseif($guessesMade <= ($highestPreviousScore + 10)){

        $result2 = db_query("INSERT INTO `highscore` (`user_initials`,`score`, `date`) VALUES ($initials, $guessesMade, now())"); 
        echo "<br><h2>You didn't beat the best score but you made the leaderboard! Congratulations!</h2>";
        echo "<a href = 'http://guess3.christopheradams.com'>Play again?</a>";
        // Call function to display scores:
        display_scores();
     }
    
      
      else{
        echo "You didn't make the leaderboard! But try again!<br>";
        echo "<a href = 'http://guess3.christopheradams.com'>Play again?</a>";
        // Call function to display scores:
        display_scores();
      }
    
  }
  ?>

  
<?php
/**
 * Database functions for a MySQL with PHP
 * Christopher Adams 12/20/2016 
 */
  
 
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

 
  echo "<a href = 'http://guess3.christopheradams.com'>Play again?</a>";
}

?>
</body>
</html>