<?php

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $username = $_POST["username"] ?? "";

    $role = "user";

    $errorMessage = "";
    
    // Check all fields are filled up
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($email) || empty($password) || empty($username)) {
            $errorMessage = "Please fill in all the fields.";
        } else {
            // Check if email already exists
            $userFile = "./data/users.txt";
            $users = file($userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($users as $user) {
                list($existingRole, $existingEmail, $existingPassword, $existingUsername) = explode(', ', $user);

                if ($email === $existingEmail) {
                    $errorMessage = "An account with this email already exists.";
                    break;
                }
            }

            // If no error message, create a new account
            if (empty($errorMessage)) {
                $fp = fopen("./data/users.txt", "a");
                fwrite($fp, "\n{$role}, {$email}, {$password}, {$username}");
                fclose($fp);

                header("Location: login.php");
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>

    <body>

        <div class="container mt-5">
            <h1 class="text-center">Create a new account</h1>

            <form action="signup.php" method="POST">

                <div class="form-group">
                    <label for="username">User name</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                </div>


                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                        placeholder="Enter email">
                </div>


                <div class="form-group">
                    <label for="email">Password</label>
                    <input type="password" class="form-control" name="password" id="email" placeholder="********">
                </div>

                <p class="text-danger">
                    <?php echo $errorMessage; ?>
                </p>

                <button type="submit" class="btn btn-success">Sign up</button></br></br>
            </form>
            <p>Already have an account? <a href="login.php">Log in</a></p>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
            crossorigin="anonymous"></script>
    </body>

</html>