<?php 
/*********************/ 
/* Database Functions
/*********************/

require_once('db_credentials.php');

// Create database connection
function db_connect() {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    confirm_db_connect();
    return $connection;
}

// Close database connection
function db_disconnect($connection) {
    if(isset($connection)) {
        mysqli_close($connection);
    }
}

// Escape dynamic data using mysqli_real_escape_string()
function db_escape($connection, $string) {
    return mysqli_real_escape_string($connection, $string);
}

// Check most recent connection for error
function confirm_db_connect() {
    if(mysqli_connect_errno()) {
        $msg = "Database connection failed: ";
        $msg .= mysqli_connect_error();
        $msg .= " (" . mysqli_connect_errno() . ")";
        exit($msg);
    }
}

// Confirm result set returned from query
function confirm_result_set($result_set) {
    if(!$result_set) {
        exit("Database query failed.");
    }
}

?>
