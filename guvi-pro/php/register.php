<?php

$name = $_POST['name'];
$email  = $_POST['email'];
$upswd1 = $_POST['password'];
$upswd2 = $_POST['upswd2'];
$age = $_POST['age'];
$dob = $_POST['dob'];
$phno = $_POST['phno'];


// Connect to MongoDB server
/*$client = new MongoDB\Client('mongodb://localhost:27017');

// Select MongoDB database and collection
$db = $client->guvi;
$collection = $db->register;
*/
// Connect to MySQL database
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "guvi_db";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check if connection to MySQL database was successful
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}

// Check if email and password were provided in the form
if (!empty($email) && !empty($upswd1)) {

    // Check if password and confirm password fields match
    if ($upswd1 != $upswd2) {
        echo "Password and Confirm Password fields do not match!";
    } else {

        // Check if email already exists in the MySQL database
        $SELECT = "SELECT email FROM register WHERE email = ? LIMIT 1";
        $INSERT = "INSERT INTO register (name, email, password, age, dob, phno) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum == 0) {
            $stmt->close();

            // Insert user information into MySQL database
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssssss", $name, $email, $upswd1, $age, $dob, $phno);
            $stmt->execute();

            // Insert user information into MongoDB database
            $result = $collection->insertOne([
                'name' => $name,
                'email' => $email,
                'password' => $upswd1,
                'age' => $age,
                'dob' => $dob,
                'phno' => $phno
            ]);

            if ($result->getInsertedCount() > 0) {
                echo "New record inserted successfully!";
            } else {
                echo "Error inserting record into MongoDB database.";
            }

            $stmt->close();
        } else {
            echo "Someone has already registered using this email address.";
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required!";
}

?>
