<?php
    require_once("config.php"); // To connect to the database

    // update w/ button
    $tid = $conn->real_escape_string($_POST['titleRating']);

    // Get movie information
    // $sql = "SELECT * FROM Title WHERE tid='$tid'";
    // $result = $conn->query($sql);

    // echo($result);

    $username = $conn->real_escape_string($_POST['username']);
    $rating = $conn->real_escape_string($_POST['rating']);

    $sql2 = "INSERT INTO Rates (tid, username, user_rating) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql2);
    $stmt->bind_param("ssd", $tid, $username, $rating);
    $stmt->execute();
    printf("%d Row inserted.\n", $stmt->affected_rows);

    /* close statement and connection */
    $stmt->close();
    
    mysqli_close($conn);
?>
