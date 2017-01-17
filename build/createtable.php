<?php
$link = mysqli_connect("localhost", "mo_admin", "mujWxcCp31qyDQpN", "mail_online_db");

/* check connection */
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Create table
// $sql = "CREATE TABLE demo(
//     id INT(6) NOT NULL PRIMARY KEY,
//     tennis INT(3),
//     china INT(3)
//     )";

$sql = "INSERT INTO demo (id, tennis, china)
VALUES ('John', 'Doe', 'john@example.com')";

if(mysqli_query($link, $sql)){
    echo "Table demo created successfully";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>
