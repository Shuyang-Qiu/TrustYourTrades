<?php
//connect to mysql

$servername = "localhost";
$username = "root";
$password = "";
//disable mysqli error report
mysqli_report(MYSQLI_REPORT_OFF);
// Create connection
//i here means improved
$conn = new mysqli($servername, $username, $password);
// Check connection
if($conn->connect_error){
    //Print a message and terminate the current script:
    die("Connection failed: ".$conn->connect_error);
}

$sql = "CREATE DATABASE tyt"; //tyt = trust your trades

if($conn->query($sql) === FALSE){
    echo "Error creating database: " . $conn->error."<br>";
} 

$sql = "USE tyt;";

if($conn->query($sql) === FALSE){
    echo "Error changing database: " . $conn->error."<br>";
} 

//users table
$sql = "CREATE TABLE users( user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, first_name VARCHAR(255) NOT NULL DEFAULT 'First', last_name VARCHAR(255) NOT NULL DEFAULT 'Last', is_contractor BOOLEAN DEFAULT FALSE, username VARCHAR(255) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL);";

if($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error."<br>";
} 


//services table
$sql = "CREATE TABLE services( user_id INT NOT NULL, service_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, service_name VARCHAR(255) NOT NULL, service_time DATETIME NOT NULL, service_address VARCHAR(255) NOT NULL, service_city VARCHAR(255) NOT NULL, service_state VARCHAR(255) NOT NULL, service_zip INT NOT NULL, service_desc TEXT NOT NULL, FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE);";

if($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error."<br>";
} 


//reviews table
$sql = "CREATE TABLE reviews( review_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, reviewer_id INT NOT NULL, reviewee_id INT NOT NULL, rating INT NOT NULL, comment TEXT NOT NULL, FOREIGN KEY (reviewer_id) REFERENCES users(user_id), FOREIGN KEY (reviewee_id) REFERENCES users(user_id));";

if($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error."<br>";
} 


//certs table (reworked with id(PK) to prevent dupe deletion/dupe certs)
$sql = "CREATE TABLE certificates( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, cert_id INT NOT NULL, cert_name VARCHAR(255) NOT NULL DEFAULT 'Certificate Name', issuing_state VARCHAR(255) NOT NULL DEFAULT 'State', expiry DATE, FOREIGN KEY (user_id) REFERENCES users(user_id));";

if($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error."<br>";
} 


//invoices table
$sql = "CREATE TABLE invoices( invoice_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, sender_id INT NOT NULL, receiver_id INT NOT NULL, service_id INT NOT NULL, price DECIMAL(10,2) NOT NULL DEFAULT 0.00, status VARCHAR(255) NOT NULL DEFAULT 'Pending', date DATE NOT NULL, FOREIGN KEY (sender_id) REFERENCES users(user_id), FOREIGN KEY (receiver_id) REFERENCES users(user_id), FOREIGN KEY (service_id) REFERENCES services(service_id));";

if($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error."<br>";
} 


$conn->close(); 
?> 
