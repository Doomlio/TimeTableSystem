<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/asset/adminlogin.css">
    <link rel="stylesheet" href="/asset/valo-button.css">
    <title>Login</title>
</head>

<body>
    <div class="container-admin-login">
        <div class="loginbox">
            <h1 class="AGT">Welcome to AGT </h1>
            <form method="post" action="loginprocessing.php">
                <div class="label-container">
                    <p class="username">
                        Email:
                    </p>
                    <input type="email" class="write-box" name="email"><br>
                </div>
                <div class="label-container">
                    <p class="password">Password:</p>

                    <input type="password" class="write-box" name="password"><br>
                </div>
                <div class="btncontainer">
                    <button type="submit" name="login">
                        <div>
                            <span></span>
                            <a>Login</a>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>