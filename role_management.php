<?php

    session_start();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    $errorMessage = "";

    // Handle new created files
    $username = $_POST["username"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? ""; 
    $role = $_POST["new_role"] ?? "";

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

                header("Location: role_management.php");
            }
        }
    }


    // Function to update user roles in the users.txt file
    function updateUserRoles($username, $newRole, $userFile) {
        $users = file($userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updatedUsers = [];

        foreach ($users as $user) {
            list($role, $email, $password, $uname) = explode(', ', $user);

            if ($uname === $username) {
                $role = $newRole; // Update the role
            }

            $updatedUsers[] = "$role, $email, $password, $uname";
        }

        file_put_contents($userFile, implode("\n", $updatedUsers));
    }

    // Handle role management actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create']) || isset($_POST['edit'])) {
            $newRole = $_POST['new_role'];
            $username = $_POST['username'];

            $userFile = './data/users.txt';
            updateUserRoles($username, $newRole, $userFile);

            header("Location: role_management.php");
        } elseif (isset($_POST['delete'])) {
            $username = $_POST['username'];

            $userFile = './data/users.txt';
            $users = file($userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $updatedUsers = [];

            foreach ($users as $user) {
                list($role, $email, $password, $uname) = explode(', ', $user);

                if ($uname !== $username) {
                    $updatedUsers[] = "$role, $email, $password, $uname";
                }
            }

            file_put_contents($userFile, implode("\n", $updatedUsers));

            header("Location: role_management.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Role Management</title>
        <!-- Include Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">Role Management</h1></br></br>

            <a href="home_admin.php" class="btn btn-primary">Admin Panel</a>
            <a href="logout.php" class="btn btn-danger">Logout</a></br></br>

            <!-- Create User Role -->
            <h2>Create New User</h2>

            <form action="role_management.php" method="POST">

            <div class="form-group">
                <label for="username">User name</label>
                <input type="text" class="form-control" name="username" id="username"  placeholder="Enter username">            
            </div>


            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">            
            </div>


            <div class="form-group">
                <label for="email">Password</label>
                <input type="password" class="form-control" name="password" id="email" placeholder="******">
            </div>
            
            <div class="form-group">
                        <label for="new_role">Role:</label>
                        <select class="form-control" name="new_role" id="new_role" required>
                            <option value="user">User</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
            </div></br>
            
            <p class="text-danger">
                    <?php echo $errorMessage; ?>
            </p>

            <button type="submit" class="btn btn-success" name="create_user">Create New User</button>
            </form></br></br>


            <!-- User Data Table -->
            <h2>Edit User Role</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th> <!-- Add a column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $userFile = './data/users.txt';
                        $users = file($userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($users as $user) {
                            list($role, $email, $password, $uname) = explode(', ', $user);
                            echo "<tr>";
                            echo "<td>$role</td>";
                            echo "<td>$email</td>";
                            echo "<td>$uname</td>";
                            echo '<td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="username" value="' . $uname . '">
                                        <select class="form-control" name="new_role">
                                            <option value="user">User</option>
                                            <option value="manager">Manager</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-warning" name="edit">Update</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="username" value="' . $uname . '">
                                        <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                                    </form>
                                </td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    </body>
</html>
