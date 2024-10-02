<?php

session_start();
include('../../includes/config.php');

if (isset($_POST['submit'])) {
    $error = null;

    $username = $_POST['username'];

    $password = $_POST['password'];

    $query = $connection->prepare("SELECT * FROM users WHERE username=:username");

    $query->bindParam("username", $username, PDO::PARAM_STR);

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {

        $error = "Username doesn't exist!";
    } else {

        if (password_verify($password, $result['password'])) {

            $_SESSION['user_id'] = $result['id'];

            header('Location: dashboard.php');
        } else {

            $error =  "Username password and password doesn't match!";
        }
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../../global.css">
    <title>Login</title>
</head>

<body>
    <div class="main-container">
        <div class="img-container">
            <img src="../images/logo.jpg" alt="Business logo" />
        </div>

        <div>

        </div>
        <div class="content-container">
            <h1>Log In</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

                <label for="username">Username: <span class="red-color">*</span></label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password: <span class="red-color">*</span></label>
                <input type="password" name="password" id="password" required>

                <div class="btn-div">
                    <input type="submit" name="submit" value="Login" id="submit-btn" />
                </div>

                <p class="red-color error-msg">
                    <?php
                    if (isset($error) || !empty($error)) {
                        echo $error;
                    }
                    ?>
                </p>

            </form>

        </div>
    </div>
</body>

</html>