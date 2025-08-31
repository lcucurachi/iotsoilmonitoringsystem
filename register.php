<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link id="theme-style" rel="stylesheet" href="css/style.css">
    <style type="text/css">
        .outer {
            display: table;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
        }

        .middle {
            display: table-cell;
            vertical-align: middle;
        }

        .inner {
            border: 3px solid green;
            padding: 10px;
            margin-left: auto;
            margin-right: auto;
            width: 400px;
            background-color: greenyellow;
            /*whatever width you want*/
        }
    </style>
</head>

<body>
    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>
    <div class="outer">
        <div class="middle">
            <div class="inner">
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account.</p>
                <form action="" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" value="">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" value="">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit" disabled>
                        <input type="reset" class="btn btn-default" value="Reset" disabled>
                    </div>
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
