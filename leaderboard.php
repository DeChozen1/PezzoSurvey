<?php

require_once "./database.php";
file:///home/s1r4j/Downloads/WhatSie/survey/survey/database.php

if (isset($_POST['all_data'])) {

    $sql = "SELECT * FROM leaderboard ORDER BY slices DESC, created_at ASC LIMIT 10";
    $records = $conn->query($sql);
    $data = $records->fetch_all(MYSQLI_ASSOC);
    $output = '';
    foreach ($data as $key => $record) {
        $key++;
        $image = '';
        $imgBlock = '';
        if ($record['image'] && !empty($record['image'])) {
            $image = $record['image'];
            $imgBlock = '<img class="image" src="' . $image . '">';
        }
        $date = date('d-m-Y h:i A', strtotime($record['created_at']));
        $date_gmt8 = date('d-m-Y h:i A', strtotime($date) + 60 * 60 * 8); //+8 hours for SGT
        $fullName = $record['fullName'];
        $id = $record['id'];
        $email = $record['email'];
        $slices = $record['slices'];
        $output .= "
        <tr>
            <th> $key </th>
            <!-- td>$imgBlock</td-->
            <th>$fullName</th>
            <!-- td> $email</td-->
            <th style=\"text-align: right\">$slices</th>
            <td>$date_gmt8</td>
        </tr>
        ";
    }

    echo $output;

    $conn->close();
    exit();
}
