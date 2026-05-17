<?php
session_start();
include('../config/database.php');

$error = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";

    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        // Verify hashed password
        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../pages/dashboard.php");
            exit();

        } else {

            $error = "Invalid Password";

        }

    } else {

        $error = "User not found";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-4">

            <div class="card p-4">

                <h2 class="mb-3">Login</h2>

                <?php if($error != ""): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

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
                            name="login"
                            class="btn btn-success w-100">
                        Login
                    </button>

                </form>

                <div class="mt-3 text-center">
                    <a href="register.php">Create Account</a>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>