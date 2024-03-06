<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $profilePicture = ''; // Default empty profile picture

    // Check if a file was uploaded
    if ($_FILES['profilePicture']['error'] === 0) {
        $targetDir = "../Asset/pp/"; // Change this to your upload directory
        $random_filename = uniqid(); // Generate a unique ID for the file name
        $targetFile = $targetDir . $random_filename . "." . pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['profilePicture']['tmp_name']);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES['profilePicture']['size'] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES['profilePicture']['name'])) . " has been uploaded.";
                $profilePicture = $random_filename . "." . pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check if username or email already exists in the database
    $sql_check = "SELECT * FROM user WHERE username='$username' OR email='$email'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "Username or email already exists.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user data into the database
        $sql_insert = "INSERT INTO user (username, password, email, nama_lengkap, alamat, profile_picture, registration_date) 
                       VALUES ('$username', '$hashed_password', '$email', '$nama_lengkap', '$alamat', '$profilePicture', NOW())";

        if ($conn->query($sql_insert) === TRUE) {
            echo "Register successful!";

            // Redirect to registration.php after successful registration
            header("Location: ../register.php");
            exit();
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>