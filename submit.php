<?php

require_once "./database.php";

if (isset($_POST['fullName'])) {

    $message = '';
    $error = '';

    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $slices = $_POST['slices'];
    $date = date('Y-m-d H:i:s');

    $destPath = '';
    if (isset($_FILES['receipt']) && $_FILES['receipt']['size'] > 0) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['receipt']['tmp_name'];
        $fileName = $_FILES['receipt']['name'];
        $fileSize = $_FILES['receipt']['size'];
        $fileType = $_FILES['receipt']['type'];

        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // directory in which the uploaded file will be moved
        $uploadFileDir = './uploads/';
        $destPath = $uploadFileDir . $newFileName;
        if(move_uploaded_file($fileTmpPath, $destPath)) {
        } else {
            $error = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
    }

    // Check if the Full Name and Email Address already exist in the database
    $sql = "SELECT * FROM leaderboard WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User already exists, update the number of slices for that individual
        $row = $result->fetch_assoc();
        $slices += $row['slices'];
        
        //Check for timestamp
        $created_at_plus_six_hours = date('Y-m-d H:i:s', strtotime($row['created_at']) + 60 * 60 * 6);
        $created_at_plus_six_hours_gmt8 = date('Y-m-d H:i:s', strtotime($created_at_plus_six_hours) + 60 * 60 * 8); //+8 hours for SGT
        if ($created_at_plus_six_hours > $date) {
            $error = "Error: Only 1 submission every 6 hours allowed. Please upload after " . $created_at_plus_six_hours_gmt8;
            
            //Remove the newly uploaded file 
            unlink($destPath);
            
            echo json_encode([
                'success' => $message,
                'error' => $error
            ]);
            
            exit();
        }
        
        if (!empty($row['image']) && file_exists($row['image'])) {
            //remove old image
            //unlink($row['image']);
        }
        $sql = <<<EOD
        -- Insert old records to leaderboard_historical
        INSERT INTO leaderboard_historical(leaderboard_id, fullName, email, slices, image, created_at)
        SELECT id, fullName, email, slices, image, created_at
        FROM leaderboard
        WHERE email = '$email';
        EOD;
        
        $sql2 = <<<EOD2
        -- Update existing leaderboard
        UPDATE leaderboard SET slices = $slices, image='$destPath', created_at = '$date' WHERE email = '$email';
        EOD2;
        
        
        // $conn->query($sql);

        if ($conn->query($sql) === TRUE) {
            // $conn->query($sql2);
        
            if ($conn->query($sql2) === TRUE) {
                $message = "record updated successfully";
                $error = '';
            } else {
                $error = "Error: " . $sql2 . "<br>" . $conn->error;
            }
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }

        
    } else {
        // User does not exist, insert a new record
        $sql = "INSERT INTO leaderboard (fullName, email, slices, image, created_at) VALUES ('$fullName', '$email', $slices, '$destPath', '$date')";

        if ($conn->query($sql) === TRUE) {
            $message = "record inserted successfully";
            $error = '';
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();

    echo json_encode([
        'success' => $message,
        'error' => $error
    ]);

    exit();
}