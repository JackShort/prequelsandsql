<?php
    require_once("config.php"); // To connect to the database

    // update w/ button
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $age = $conn->real_escape_string($_POST['age']);
    $genre = $conn->real_escape_string($_POST['genre']);

    // Get movie information
    // $sql = "SELECT * FROM User WHERE username COLLATE UTF8_GENERAL_CI LIKE '%".$username."%'";
    // $result = $conn->query($sql);
    // $data = array();
    
    // if ($result->num_rows = 0) {
        $sql2 = "INSERT INTO User (username, password, age, favorite_genre) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql2);
        $stmt->bind_param("ssds", $username, $password, $age, $genre);
        $stmt->execute();
        printf("%d Row inserted.\n", $stmt->affected_rows);
        /* close statement and connection */
        $stmt->close();
    // }

    mysqli_close($conn);
?>
