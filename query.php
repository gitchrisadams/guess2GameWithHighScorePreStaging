<?php
    // Connect to the database
   $connection = db_connect();

    // An insertion query. $result will be `true` if successful
    $result = db_query("INSERT INTO `users` (`name`,`email`) VALUES ('John Doe','john.doe@gmail.com')");
    if($result === false) {
        // Handle failure - log the error, notify administrator, etc.
    } else {
        // We successfully inserted a row into the database
    }
?>