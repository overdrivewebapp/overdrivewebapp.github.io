<?php
require "includes/dbconnections.php";
require "includes/header.php";
if (isset($_SESSION['id'])) {
    if ($_SESSION['type'] == 1) {
        header("location: admin/profile.php");
    } elseif ($_SESSION['type'] == 2) {
        header("location: user/file_manager.php");
    }
}

if (!empty($_POST)) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];
	$_SESSION['error_message'] = "";
	
    $user_check = $db->query("SELECT * FROM accounts WHERE username = '$username'");
    if ($user_check->num_rows > 0) {
        $_SESSION['error_message'] .= "Username Registered<br>";
    }
    
    $email_check = $db->query("SELECT * FROM accounts WHERE email = '$email'");
    if ($email_check->num_rows > 0) {
        $_SESSION['error_message'] .= "Email Registered<br>";
    }

    if ($password != $cpassword) {
        $_SESSION['error_message'] .= "Passwords Dont Match<br>";
    }

    if (empty($_SESSION['error_message'])) {
        $date = time();
        +$dir = strtolower($username.$fname);
        mkdir("files/$dir");
        $db->query("INSERT INTO accounts (`firstname`,`lastname`,`username`,`password`,`type`,`email`,`date_created`,`files_folder`)"
                . " VALUES ('$fname','$lname','$username','$password',2,'$email',$date,'$dir')") or die($db->error);
        $_SESSION['register_ok'] = TRUE;
        audit("$fname $lname Registered an Account");
    }
}
?>

<div class="row" >
    <div class="col-xs-6 col-xs-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="text-center text-danger"> OverDrive Registration </h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($_SESSION['error_message'])) { ?>
                    <div class="alert alert-danger"> 
                        <h2> <?php echo $_SESSION['error_message'] ?></h2>
                    </div>
                    <?php
                    unset($_SESSION['error_message']);
                } 
				if (isset($_SESSION['register_ok'])) {
                    ?>
                    <div class="alert alert-success text-center"> 
                        <h2>Account Registered</h2>
                    </div>
                    <?php
                    unset($_SESSION['register_ok']);
                }
                ?>
                <form method="post" action="registration.php" class="row">
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
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" required />
                    </div>
                    <div class="form-group col-xs-12">
                        <br>
                        <input type="submit" value="Register" class="btn btn-success btn-block"/>
						<br>
						 <a href="index.php" class="btn btn-warning btn btn-block" > Login </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
	       
</div>

<?php
require "includes/footer.php";
?>
 



