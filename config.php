<?php
    $SERVER = 'cs4750.cs.virginia.edu';
    $USERNAME = 'jcs4ua';
    $PASSWORD = 'copperhead12';
    $DATABASE = 'jcs4ua';
    // Create database connection
    $conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
