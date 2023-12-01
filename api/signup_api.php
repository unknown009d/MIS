<?php

include 'connect.php';

function generateRandomEmpID() {
    // Generate a random number between 0 and 9999
    $randNumber = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    return $randNumber;
}

// Function to check if the generated emp_id already exists in the table
function empIDExists($emp_id, $conn) {
    $sql = "SELECT emp_id FROM tbl_employee WHERE emp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the request
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data["username"];
    $password = $data["password"];
    $name = $data["name"];

	do {
		$newEmpID = generateRandomEmpID();
	} while (empIDExists($newEmpID, $conn));	

    // Check if the username is already taken
    $stmt = mysqli_prepare($conn, "SELECT id FROM tbl_employee WHERE uid=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $response = array("success" => false, "message" => "Username already taken");
    } else {
        // Hash the password
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = mysqli_prepare($conn, "INSERT INTO tbl_employee (emp_id,e_name,uid, upwd) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $newEmpID, $name, $username, $password);
        mysqli_stmt_execute($stmt);

        $response = array("success" => true, "message" => "Account Created Successfully");
    }

    // Send the JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
}

// Close the connection
mysqli_close($conn);

?>
