<?php
    session_start();

    if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
        header("Location: login.php");
    }

    // Read user data from the file
    $users = [];
    $fp = fopen("./data/users.txt", "r");
    while ($line = fgets($fp)) {
        $userData = explode(", ", $line);
        $users[] = [
            "role" => $userData[0],
            "email" => $userData[1],
            "password" => $userData[2],
            "username" => $userData[3],
        ];
    }
    fclose($fp);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Panel</title>
        <!-- Add Bootstrap CSS link here -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
    <body>
        
        <div class="container mt-5">
            <h1 class="text-center">User Panel</h1>
            <h2>Welcome, <?php echo $_SESSION["username"] . "!"; ?></h2>
            <h4>Role: <?php echo $_SESSION["role"]; ?></h4>
            
            <a href="logout.php" class="btn btn-danger">Logout</a></br></br>

            <h3 class="mt-4">Your Fellows</h3>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user["username"]; ?></td>
                            <td><?php echo $user["role"]; ?></td>
                            <td><?php echo $user["email"]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
