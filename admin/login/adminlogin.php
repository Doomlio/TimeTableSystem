<!DOCTYPE html>
<html>
<!---->

<head>
    <link rel="stylesheet" href="/asset/adminlogin.css">
    <link rel="stylesheet" href="/asset/valo-button.css">
    <title>Login</title>
    <a href="/lecturer/login/login.php"> lecturer login</a>
</head>

<body>
    <div class="container-admin-login">
        <div class="loginbox">
            <h1 class="AGT">WELCOME</h1>
            <h1 class="AGT">TO</h1>
            <h1 class="AGT">AGT (ADMIN)</h1>
            <form method="post" action="/admin/login/adminloginprocessing.php">
                <div class="label-container">
                    <input type="email" name="email" placeholder="Email">
                    <br>
                </div>
                <div class="label-container">
                    <input type="password" name="password" placeholder="Password">
                    <br>
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