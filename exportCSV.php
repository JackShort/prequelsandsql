<?php
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=searchResults.csv");

    require_once("config.php"); // To connect to the database
    
    // create a file pointer connected to the output stream
    $output = fopen("php://output", "w");
    
    // output the column headings
    fputcsv($output, array('Title', 'tID', 'Rating', 'User Rating'));

    $title = $conn->real_escape_string($_POST['title']);
    $sql ="SELECT Title.title, Title.tid, Title.rating, AVG(Rates.user_rating) FROM Title LEFT JOIN Rates ON Title.tid=Rates.tid WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%' GROUP BY Title.title";
    $result = $conn->query($sql);
    
    // loop over the rows, outputting them
    while ($row = $result->fetch_assoc()) fputcsv($output, $row);
    fclose($output);
?>