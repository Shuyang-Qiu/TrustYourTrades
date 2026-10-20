<?php

//connect to the database
function db_connect(){
    $conn = new mysqli("localhost", "root", "", "tyt");

    if($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
    }

    return $conn;
}


//get a single service by  id (for service_info)
//bind_param as security measure to prevent sql injection,binds values to placeholders
//not necessary, but experimenting
function get_service_by_id($service_id){
    $conn = db_connect();

    $stmt = $conn->prepare("
        SELECT services.*, users.first_name, users.last_name, users.username
        FROM services
        INNER JOIN users ON services.user_id = users.user_id
        WHERE services.service_id = ?
    ");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    return $service;
}


//function for searching services (based on most matching search params)
//keyword, contractor, address, city, state, zip, date
//% = "Like" (matching), s = string, i = integer, .= append
//implode = joins array into string with a separator 
//this function may not be the most effficient, but i wanted to make it possible to search by specifics
//can leave inputs empty, builds query dynamically (https://www.sitepoint.com/community/t/how-to-generate-a-dynamic-mysqli-query/220430/3)
function search_services($keyword, $contractor, $address, $city, $state, $zip, $date, $time){
    $conditions = []; //holds where
    $params = []; //holds values
    $types = ""; //holds string of type for bind_param

    if(!empty($keyword)){
        $conditions[] = "services.service_name LIKE ?";
        $params[] = "%".$keyword."%";
        $types .= "s";
    }

    if(!empty($contractor)){
        //CONCAT joins strings (wish i paid more attention in database systems)
        $conditions[] = "(users.first_name LIKE ? OR users.last_name LIKE ? OR CONCAT(users.first_name, ' ', users.last_name) LIKE ?)";
        $params[] = "%".$contractor."%";
        $params[] = "%".$contractor."%";
        $params[] = "%".$contractor."%";
        $types .= "sss";
    }
    if(!empty($address)){
        $conditions[] = "services.service_address LIKE ?";
        $params[] = "%".$address."%";
        $types .= "s";
    }
    if(!empty($city)){
        $conditions[] = "services.service_city LIKE ?";
        $params[] = "%".$city."%";
        $types .= "s";
    }
    if(!empty($state)){
        $conditions[] = "services.service_state LIKE ?";
        $params[] = "%".$state."%";
        $types .= "s";
    }
    if(!empty($zip)){
        $conditions[] = "services.service_zip = ?";
        $params[] = intval($zip);
        $types .= "i";
    }
    //maybe separate datetime column into two columsn for date and time? queries are getting a bit messy when together
    if(!empty($date) && !empty($time)){
        $conditions[] = "DATE(services.service_time) = ? AND TIME(services.service_time) = ?";
        $params[] = $date;
        $params[] = $time;
        $types .= "ss";
    }
    elseif(!empty($date)){
        $conditions[] = "DATE(services.service_time) = ?";
        $params[] = $date;
        $types .= "s";
    }
    elseif(!empty($time)){
        $conditions[] = "TIME(services.service_time) = ?";
        $params[] = $time;
        $types .= "s";
    }

    $sql = "SELECT services.*, users.first_name, users.last_name, users.username FROM services INNER JOIN users ON services.user_id = users.user_id";

    if(!empty($conditions)){
        //SPACE BEFORE WHERE IS NECESSARY!!! DO NOT REMOVE!!
        $sql .= " WHERE ".implode(" AND ", $conditions);
    }

    //SPACE BEFORE ORDER BY IS NECESSARY
    $sql .= " ORDER BY services.service_time ASC";

    $conn = db_connect();
    $stmt = $conn->prepare($sql);

    if(!empty($params)){
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();

    return $results;
}


//update user table according to user input for first/last name, contractor bool (intval in table)
function store_user_info($username, $first_name, $last_name, $is_contractor){
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = db_connect();

    $result = $conn->query("UPDATE users set first_name = '$first_name', last_name = '$last_name', is_contractor = $is_contractor WHERE username = '$username'");
    $conn->close();

    return $result;
}


//run a query and return all rows as an array--used by get_services, get_reviews, get_certificates, get_invoices
function fetch_all_rows($sql){
    $conn = db_connect();
    $result = $conn->query($sql);

    $rows = [];
    while($row = $result->fetch_assoc()){
        $rows[] = $row;
    }

    $conn->close();
    return $rows;
}


//get the full name of a user by their id
function get_reviewer_name($user_id){
    $conn = db_connect();
    $result = $conn->query("SELECT first_name, last_name FROM users WHERE user_id = $user_id");
    $row = $result->fetch_assoc();
    $conn->close();

    if($row){
        return $row["first_name"]." ".$row["last_name"];
    }
    else{
        return null;
    }
}


//get the average star rating for a contractor
function get_average_rating($user_id){
    $conn = db_connect();
    $result = $conn->query("SELECT AVG(rating) AS avg_rating FROM reviews WHERE reviewee_id = $user_id");
    $row = $result->fetch_assoc();
    $conn->close();

    return $row["avg_rating"];
}


//update a users password
function update_password($username, $hashed_password){
    $conn = db_connect();
    $conn->query("UPDATE users SET password_hash = '$hashed_password' WHERE username = '$username'");
    $conn->close();
}


//get a single user by username
function get_user($username){
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    $user = $result->fetch_assoc();
    $conn->close();

    return $user;
}


//get all invoices sent or received by user
//off-topic, this query was shorter before i added edit/remove functionality--took like 3 hours to figure out this updated query
function get_invoices($user_id){
    $sql = "SELECT 
                invoices.invoice_id,
                invoices.sender_id,
                invoices.receiver_id,
                invoices.service_id,
                invoices.price,
                invoices.status,
                invoices.date,
                services.service_name,
                receiver.username AS receiver_username,
                sender.username AS sender_username
            FROM invoices
            INNER JOIN services ON invoices.service_id = services.service_id
            INNER JOIN users receiver ON invoices.receiver_id = receiver.user_id
            INNER JOIN users sender ON invoices.sender_id = sender.user_id
            WHERE invoices.sender_id = $user_id
            OR invoices.receiver_id = $user_id
            ORDER BY invoices.date DESC";

    return fetch_all_rows($sql);
}


//get a single invoice by id--only if belongs to user
function get_invoice_by_id($invoice_id, $user_id){
    $conn = db_connect();

    $stmt = $conn->prepare("
        SELECT invoices.*, services.service_name, u.username AS receiver_username
        FROM invoices
        INNER JOIN services ON invoices.service_id = services.service_id
        INNER JOIN users u ON invoices.receiver_id = u.user_id
        WHERE invoices.invoice_id = ? AND invoices.sender_id = ?
    ");
    $stmt->bind_param("ii", $invoice_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    return $invoice;
}


//get all users
function get_users(){
    return fetch_all_rows("SELECT * FROM users;");
}

//get all services belonging to a user
function get_services($user_id){
    return fetch_all_rows("SELECT * FROM services WHERE user_id = $user_id");
}

//get all reviews for contractor
function get_reviews($user_id){
    return fetch_all_rows("SELECT * FROM reviews WHERE reviewee_id = $user_id");
}

//get all certificates belonging to a user
function get_certificates($user_id){
    return fetch_all_rows("SELECT * FROM certificates WHERE user_id = $user_id");
}


//get a single certificate by its backend id--only if belong to user
function get_certificate_by_id($id, $user_id){
    $conn = db_connect();

    $stmt = $conn->prepare("SELECT * FROM certificates WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cert = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    return $cert;
}


//check if a username and password match--for login
function check_user($username, $password){
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = db_connect();

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");

    if(!$result || $result->num_rows === 0){
        $conn->close();
        return false;
    }

    $hashed_password = $result->fetch_assoc()["password_hash"];
    $conn->close();

    return password_verify($password, $hashed_password);
}


//create a new account--only insertion is username and hash pass, other columns are handled by store_user_info()
function create_user($username, $hashed_password){
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = db_connect();

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");

    if(!$result || $result->num_rows > 0){
        $conn->close();
        return false;
    }

    $result = $conn->query("INSERT INTO users (username, password_hash) VALUES ('$username', '$hashed_password')");
    $conn->close();

    return $result;
}


//match password and password re-entry when resetting
function match_password($password, $confirm_password){
    return $password === $confirm_password;
}


//handle adding, editing, and deleting certificates
function handle_certificate_requests($user_id){
    $response = [
        "create_error" => "",
        "create_success" => "",
        "edit_error" => "",
        "edit_success" => "",
        "show" => "selection"
    ];

    //only run if the form was submitted
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return $response;
    }

    //add a new certificate
    if(isset($_POST["create_certificate"])){
        $cert_name = trim($_POST["cert_name"]);
        $cert_id = intval($_POST["cert_id"]);
        $issuing_state = trim($_POST["issuing_state"]);
        $expiry = $_POST["expiry"];

        if(!$cert_name || !$cert_id || !$issuing_state || !$expiry){
            $response["create_error"] = "All fields are required.";
            $response["show"] = "create";
            return $response;
        }

        $conn = db_connect();

        $stmt = $conn->prepare("
            INSERT INTO certificates (user_id, cert_id, cert_name, issuing_state, expiry)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisss", $user_id, $cert_id, $cert_name, $issuing_state, $expiry);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        if($result){
            $response["create_success"] = "Certificate added successfully!";
        }
        else{
            $response["create_error"] = "Failed to add certificate.";
        }

        $response["show"] = "create";
    }

    //edit an existing certificate
    if(isset($_POST["edit_certificate"])){
        $id = intval($_POST["id"]);
        $cert_id = intval($_POST["cert_id"]);
        $cert_name = trim($_POST["cert_name"]);
        $issuing_state = trim($_POST["issuing_state"]);
        $expiry = $_POST["expiry"];

        if(!$id || !$cert_id || !$cert_name || !$issuing_state || !$expiry){
            $response["edit_error"] = "All fields are required.";
            $response["show"] = "edit";
            return $response;
        }

        $conn = db_connect();

        $stmt = $conn->prepare("
            UPDATE certificates
            SET cert_id = ?, cert_name = ?, issuing_state = ?, expiry = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("isssii", $cert_id, $cert_name, $issuing_state, $expiry, $id, $user_id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        $conn->close();

        if($affected > 0){
            $response["edit_success"] = "Certificate updated successfully!";
        }
        else{
            $response["edit_error"] = "Update failed. Certificate not found.";
        }

        $response["show"] = "edit";
    }

    //delete an existing certificate
    if(isset($_POST["delete_certificate"])){
        $id = intval($_POST["id"]);

        if(!$id){
            $response["edit_error"] = "Please select a certificate to delete.";
            $response["show"] = "edit";
            return $response;
        }

        $conn = db_connect();

        $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        $conn->close();

        if($affected > 0){
            $response["edit_success"] = "Certificate deleted successfully!";
        }
        else{
            $response["edit_error"] = "Delete failed. Certificate not found.";
        }

        $response["show"] = "edit";
    }

    return $response;
}


//handle creating reviews (not editable)
function handle_review_requests($user_id){
    $response = [
        "create_error" => "",
        "create_success" => "",
        "show" => "selection"
    ];

    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return $response;
    }

    if(isset($_POST["create_review"])){
        $reviewee_username = trim($_POST["reviewee_username"]);
        $rating = intval($_POST["rating"]);
        $comment = trim($_POST["comment"]);

        if(!$reviewee_username || !$rating || !$comment){
            $response["create_error"] = "All fields are required.";
            $response["show"] = "create";
            return $response;
        }

        if($rating < 1 || $rating > 5){
            $response["create_error"] = "Rating must be between 1 and 5.";
            $response["show"] = "create";
            return $response;
        }

        $reviewee = get_user($reviewee_username);

        if(!$reviewee){
            $response["create_error"] = "User not found.";
        }
        elseif($reviewee["user_id"] == $user_id){
            $response["create_error"] = "You cannot review yourself.";
        }
        else{
            $conn = db_connect();
            //? as placeholders for bind_param
            $stmt = $conn->prepare("
                INSERT INTO reviews (reviewer_id, reviewee_id, rating, comment)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iiis", $user_id, $reviewee["user_id"], $rating, $comment);
            $result = $stmt->execute();
            $stmt->close();
            $conn->close();

            if($result){
                $response["create_success"] = "Review submitted successfully!";
            }
            else{
                $response["create_error"] = "Failed to submit review.";
            }
        }

        $response["show"] = "create";
    }

    return $response;
}


//handle creating, editing, and deleting invoices
function handle_invoice_requests($user_id){
    $response = [
        "create_error" => "",
        "create_success" => "",
        "edit_error" => "",
        "edit_success" => "",
        "show" => "selection"
    ];

    //only run if the form was submitted
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return $response;
    }

    //create a new invoice
    if(isset($_POST["create_invoice"])){
        $receiver_username = trim($_POST["receiver_username"]);
        $service_id = intval($_POST["service_id"]);
        $date = $_POST["date"];
        $price = floatval($_POST["price"]);

        if(!$receiver_username || !$service_id || !$date || $price <= 0){
            $response["create_error"] = "All fields are required.";
            $response["show"] = "create";
            return $response;
        }

        $receiver = get_user($receiver_username);

        if(!$receiver){
            $response["create_error"] = "User not found.";
        }
        elseif($receiver["user_id"] == $user_id){
            $response["create_error"] = "You cannot invoice yourself.";
        }
        else{
            $conn = db_connect();

            $result = $conn->query("
                INSERT INTO invoices (sender_id, receiver_id, service_id, price, status, date)
                VALUES ($user_id, {$receiver["user_id"]}, $service_id, $price, 'Pending', '$date')
            ");

            if($result){
                $response["create_success"] = "Invoice created successfully!";
            }
            else{
                $response["create_error"] = "Failed to create invoice.";
            }

            $conn->close();
        }

        $response["show"] = "create";
    }

    //edit an existing invoice
    if(isset($_POST["edit_invoice"])){
        $invoice_id = intval($_POST["invoice_id"]);
        $price = floatval($_POST["price"]);
        $status = $_POST["status"];

        $allowed_statuses = ["Pending", "Overdue", "Paid", "Cancelled"];

        if(!$invoice_id || $price <= 0 || !in_array($status, $allowed_statuses)){
            $response["edit_error"] = "Invalid input.";
            $response["show"] = "edit";
            return $response;
        }

        $conn = db_connect();

        $conn->query("
            UPDATE invoices
            SET price = $price, status = '$status'
            WHERE invoice_id = $invoice_id AND sender_id = $user_id
        ");

        if($conn->affected_rows > 0){
            $response["edit_success"] = "Invoice updated!";
        }
        else{
            $response["edit_error"] = "Update failed.";
        }

        $conn->close();
        $response["show"] = "edit";
    }

    //delete an existing invoice
    if(isset($_POST["delete_invoice"])){
        $invoice_id = intval($_POST["invoice_id"]);

        if(!$invoice_id){
            $response["edit_error"] = "Please select an invoice to delete.";
            $response["show"] = "edit";
            return $response;
        }

        $conn = db_connect();

        $conn->query("DELETE FROM invoices WHERE invoice_id = $invoice_id AND sender_id = $user_id");

        if($conn->affected_rows > 0){
            $response["edit_success"] = "Invoice deleted successfully!";
        }
        else{
            $response["edit_error"] = "Delete failed. Invoice not found.";
        }

        $conn->close();
        $response["show"] = "edit";
    }

    return $response;
}
?>
