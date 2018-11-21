<?php
require "../includes/dbconnections.php";
require "../includes/header.php";
if (!isset($_SESSION['id'])) {
    header("location: ../index.php");
}
if ($_SESSION['type'] != 1) {
    header("location: ../logout.php");
}


if (!empty($_POST)) {
    $_SESSION['error_message'] = "";
    $fname = strip_tags($_POST['fname']);
    $lname = strip_tags($_POST['lname']);
    $username = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);
    $type = strip_tags($_POST['type']);
    $email = strip_tags($_POST['email']);

    $user_check = $db->query("SELECT * FROM accounts WHERE username = '$username'");
    if ($user_check->num_rows > 0) {
        $_SESSION['error_message'] .= "Username Registered<br>";
    }
    
    $email_check = $db->query("SELECT * FROM accounts WHERE email = '$email'");
    if ($email_check->num_rows > 0) {
        $_SESSION['error_message'] .= "Email Registered<br>";
    }

    if (empty($_SESSION['error_message'])) {
        $date = time();
        $dir = strtolower($username . $fname);
        mkdir("../files/$dir");
        $db->query("INSERT INTO accounts (`firstname`,`lastname`,`username`,`password`,`type`,`email`,`date_created`,`files_folder`)"
                        . " VALUES ('$fname','$lname','$username','$password',$type,'$email',$date,'$dir')") or die($db->error);
        $_SESSION['create_ok'] = TRUE;
        audit("Created Account For $fname $lname");
    }
}
?>

<div class="row" >
    <div class="col-xs-6 col-xs-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h2>
                    <span class="text-danger"> Create Account </span>
                </h2>
            </div>
            <div class="panel-body">    
                <?php if (isset($_SESSION['error_create'])) { ?>
                    <div class="alert alert-danger" style="display:block;"> 
                        <?php echo $_SESSION['error_create'] ?>
                    </div>
                    <?php
                    unset($_SESSION['error_create']);
                } elseif (isset($_SESSION['create_ok'])) {
                    ?>
                    <div class="alert alert-success" style="display:block;"> 
                        Account Created
                    </div>
                    <?php
                    unset($_SESSION['create_ok']);
                }
                ?>

                <form method="post" action="account_create.php" class="row">
                    <div class="form-group col-xs-6">
                        <label>First Name</label>
                        <input type="text" name="fname" class="form-control" required />
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Last Name</label>
                        <input type="text" name="lname" class="form-control" required />    
                    </div>      
                    <div class="form-group col-xs-6">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required />
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Account</label>
                        <select class="form-control" name="type">
                            <option value="1">Admin</option>
                            <option value="2" selected>User</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <br>
                        <input type="submit" value="Create" class="btn btn-success btn-block"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require "../includes/footer.php";
?>