<!-- TEST VALUE INSERTION -->

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

$sql = "USE tyt;";

if($conn->query($sql) === FALSE){
    echo "Error changing database: " . $conn->error."<br>";
} 

//====USERS TABLE==== (auto_inc, first, last, is_contractor, username, pass_hash)

$pass_hash = password_hash("test", PASSWORD_DEFAULT);
$sql = "INSERT INTO users VALUES (NULL, 'Bob', 'Smith', true, 'smithb', '$pass_hash');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO users VALUES (NULL, 'Kelly', 'Orion', false, 'orionk', '$pass_hash');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO users VALUES (NULL, 'Emily', 'Williams', true, 'williamse', '$pass_hash');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO users VALUES (NULL, 'Ryan', 'Johnson', false, 'johnsonr', '$pass_hash');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 


//====SERVICES TABLE==== (user, auto_inc, title, datetime, addres, city, state, zip, desc)

//bob
$desc = "Basic equipment on-hand, looking for lawns between 1-10 acres, but willing to rent equipment for larger areas.";
$sql = "INSERT INTO services VALUES (1, NULL, 'Lawn Mowing', '2026-07-03 18:45:00', '4187 Maple Ridge Drive', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$desc = "Professional plumbing services since 1998. Can fix any shower, sink, or toilet clog. Don''t risk spending thousands of dollars for a full plumbing system repair!";
$sql = "INSERT INTO services VALUES (1, NULL, 'Plumbing', '2026-05-29 16:30:00', '4187 Maple Ridge Drive', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$desc = "Carpets, walls, doors, windows... you name it! I''m a professional carpenter with over 20 years of experience. I''ll bring the tools if you provide the supplies!";
$sql = "INSERT INTO services VALUES (1, NULL, 'Basic Carpentry', '2026-09-27 07:15:00', '4187 Maple Ridge Drive', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$desc = "General repairs including drywall patching, fixture replacement, minor plumbing, and furniture assembly. Fully stocked with standard tools. Best suited for small-to-medium jobs that can be completed in a day. Reliable and detail-oriented.";
$sql = "INSERT INTO services VALUES (1, NULL, 'Handyman Services', '2026-06-01 12:30:00', '4187 Maple Ridge Drive', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
//emily
$desc = "5+ years experience with residential painting. Own brushes, rollers, sprayers, and ladders up to 2 stories. Comfortable with prep work (patching, sanding, priming). Prefer single-family homes but open to small commercial jobs. Can help with color selection if needed.";
$sql = "INSERT INTO services VALUES (3, NULL, 'House Painting', '2026-07-22 03:15:00', '905 Cedar Hollow Lane', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$desc = "Own a trailer and can haul furniture, appliances, yard waste, and construction debris. Able to lift heavy items with help if needed. Focused on quick turnaround jobs—garage cleanouts, moving prep, or renovation cleanup.";
$sql = "INSERT INTO services VALUES (3, NULL, 'Junk Removal', '2026-05-14 09:15:00', '905 Cedar Hollow Lane', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}
$desc = "Commercial-grade pressure washer available for driveways, siding, decks, and patios. Experienced with proper settings for different surfaces to avoid damage. Ideal for seasonal cleanups or pre-sale home prep.";
$sql = "INSERT INTO services VALUES (3, NULL, 'Pressure Washing', '2026-09-02 14:45:00', '905 Cedar Hollow Lane', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$desc = "Experience installing wood and vinyl fencing. Have post hole digger and standard carpentry tools. Can handle repairs (loose posts, broken panels) or small new installs. Larger projects possible with scheduling.";
$sql = "INSERT INTO services VALUES (3, NULL, 'Fence Installation & Repair', '2026-08-01 12:30:00', '905 Cedar Hollow Lane', 'Eau Claire', 'Wisconsin', 54701, '$desc');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 


//====REVIEWS TABLE==== (auto_inc, sender, receiver, score, comment)

//for bob
$comment = "Showed up early, worked efficiently, and everything turned out exactly how I hoped. Zero issues.";
$sql = "INSERT INTO reviews VALUES(NULL, 2, 1, 5, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "Left the job unfinished and stopped responding to messages. Had to hire someone else.";
$sql = "INSERT INTO reviews VALUES(NULL, 2, 1, 1, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "Solid experience. Communication was good and the job was done well, just took a bit longer than expected.";
$sql = "INSERT INTO reviews VALUES(NULL, 4, 1, 4, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
//for kelly
$comment = "Very difficult client. Unclear instructions and unresponsive during critical parts of the project.";
$sql = "INSERT INTO reviews VALUES(NULL, 1, 2, 1, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "Payment took longer than agreed and needed multiple follow-ups. Job eventually completed, but it was frustrating.";
$sql = "INSERT INTO reviews VALUES(NULL, 1, 2, 2, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}
$comment = "Friendly and respectful. Project went well, just needed a bit more clarification at the start.";
$sql = "INSERT INTO reviews VALUES(NULL, 3, 2, 4, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}
$comment = "Smooth experience for the most part. A couple minor delays in feedback, but nothing that caused issues.";
$sql = "INSERT INTO reviews VALUES(NULL, 3, 2, 4, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}
//for emily
$comment = "Not bad, not great. Results were okay but didn''t fully meet expectations.";
$sql = "INSERT INTO reviews VALUES(NULL, 2, 3, 3, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "No-showed without any notice. Completely unreliable.";
$sql = "INSERT INTO reviews VALUES(NULL, 2, 3, 1, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "Couldn''t have asked for better. Everything was handled smoothly and the results were great.";
$sql = "INSERT INTO reviews VALUES(NULL, 4, 3, 5, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
//for ryan
$comment = "Decent client. Communication was sometimes slow, but we got through the project without major problems.";
$sql = "INSERT INTO reviews VALUES(NULL, 1, 4, 3, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$comment = "Very organized and respectful throughout the project. Knew exactly what they wanted and made the process easy.";
$sql = "INSERT INTO reviews VALUES(NULL, 3, 4, 5, '$comment');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 


//====CERTIFICATES TABLE==== (auto_inc, owner, id, title, state, exp date)

//bob
$sql = "INSERT INTO certificates VALUES(NULL, 1, 48291736, 'Electrician\'s License', 'Wisconsin', '2026-03-14');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO certificates VALUES(NULL, 1, 90518462, 'Landscaping Certification', 'Wisconsin', '2026-11-02');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO certificates VALUES(NULL, 1, 17365098, 'Plumbing License', 'Wisconsin', '2027-06-19');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
//emily
$sql = "INSERT INTO certificates VALUES(NULL, 3, 66820341, 'Plumbing License', 'Wisconsin', '2027-12-08');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO certificates VALUES(NULL, 3, 75194000, 'Carpentry Certification', 'Wisconsin', '2028-01-25');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 


//====INVOICES TABLE==== (auto_int, sender, receiver, service_id, price, status, date)

//from bob
$sql = "INSERT INTO invoices VALUES(NULL, 1, 2, 1, 130.59, 'Paid', '2025-12-07');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO invoices VALUES(NULL, 1, 2, 2, 199.13, 'Pending', '2023-07-09');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO invoices VALUES(NULL, 1, 4, 3, 370.55, 'Cancelled', '2026-02-19');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO invoices VALUES(NULL, 1, 4, 4, 74.44, 'Overdue', '2024-10-21');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 
$sql = "INSERT INTO invoices VALUES(NULL, 1, 3, 2, 174.80, 'Paid', '2023-02-18');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
} 

//from emily
$sql = "INSERT INTO invoices VALUES(NULL, 3, 2, 5, 300.00, 'Pending', '2025-03-12');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  
$sql = "INSERT INTO invoices VALUES(NULL, 3, 2, 6, 98.01, 'Paid', '2026-05-26');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  
$sql = "INSERT INTO invoices VALUES(NULL, 3, 4, 7, 109.80, 'Overdue', '2024-01-15');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  
$sql = "INSERT INTO invoices VALUES(NULL, 3, 4, 8, 277.00, 'Paid', '2026-04-11');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  
$sql = "INSERT INTO invoices VALUES(NULL, 3, 1, 8, 495.99, 'Paid', '2024-06-03');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  
$sql = "INSERT INTO invoices VALUES(NULL, 3, 1, 5, 95.99, 'Cancelled', '2025-08-30');";
if($conn->query($sql) === FALSE){
    echo "Error inserting: " . $conn->error."<br>";
}  


$conn->close(); 
?> 
