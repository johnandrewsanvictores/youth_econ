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

        <div class="content-container">
            <h1>Log In</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

                <label for="username">Username:</label>
                <input type="text" name="username" id="username">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password">

                <div class="btn-div">
                    <input type="submit" name="submit" value="Login" id="submit-btn" />
                </div>
            </form>

        </div>
    </div>
</body>

</html>