<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';

//change to Wolverine work database!!!!
$DATABASE_NAME = '';

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
// If there is an error with the connection, stop the script and display the error.
exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
// Could not get the data that should have been sent.
exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.

//REPLACE "accounts" WITH TABLE NAME FROM WOLVERINE WORK DATABASE!!!!!!!!!
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
$stmt->bind_param('s', $_POST['username']);
$stmt->execute();
// Store the result so we can check if the account exists in the database.
$stmt->store_result();
if ($stmt->num_rows > 0) {
    /*put on register page
    $stmt->bind_result($id, $email);
    $stmt->fetch();
    
    $tld = substr($email, strlen($email)-2, 9);    // three last chars of the string
    if ($tld = "umich.edu") {
        // do stuff
    }
    */

    $stmt->bind_result($id, $password);
    $stmt->fetch();
    echo '$password';
    // Account exists, now we verify the password.
    // Note: remember to use password_hash in your registration file to store the hashed passwords. (done)
    if (password_verify($_POST['password'], $password)) {
        // Verification success! User has logged-in!
        // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
        
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;

        //CHANGE NAME TO THAT OF THE HOMEPAGE IF DIFFERENT!!!!!!!!!!
        header('Location: ../home/home-page.html');
        exit;
    } else {
        // Incorrect password
        echo 'Incorrect password!';
    }
} else {
    // Incorrect username
    echo 'Incorrect username!';
}
$stmt->close();
}
?>
