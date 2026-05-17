<?php
include('../config/database.php');

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "staff";

    // Check if username already exists
    $check = mysqli_query($conn,
    "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($check) > 0){

        echo "Username already exists!";

    } else {

        $query = "INSERT INTO users
        (username, password, role)
        VALUES
        ('$username', '$password', '$role')";

        if(mysqli_query($conn, $query)){
            echo "Registration Successful!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <div class="card p-4">

        <h2>Register</h2>

        <form method="POST">

            <div class="mb-3">
                <label>Username</label>
                <input type="text"
                       name="username"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <button type="submit"
                    name="register"
                    class="btn btn-primary">
                Register
            </button>

        </form>

        <br>

        <a href="login.php">
            Already have an account? Login
        </a>

    </div>

</div>

</body>
</html>