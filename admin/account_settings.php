<?php
require "../includes/dbconnections.php";
require "../includes/header.php";
if (!isset($_SESSION['id'])) {
    header("location: ../index.php");
}
if ($_SESSION['type'] != 1) {
    header("location: ../logout.php");
}


$ID = $_SESSION['id'];
if (isset($_POST['updateAcc'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $email_check = $db->query("SELECT * FROM accounts WHERE email = '$email'");
    if ($email_check->num_rows > 0) {
        $_SESSION['error_message'] = "Email Registered<br>";
    }

    if (isset($_SESSION['error_message'])) {
        $db->query("UPDATE accounts SET  email = '$email' ,firstname = '$fname' , lastname = '$lname'"
                        . " WHERE account_id = $ID ") OR die($db->error);
        $_SESSION['updated'] = TRUE;
        audit("Updated Admin account");
    }
}
if (isset($_POST['changePass'])) {
    $ID = $_SESSION['id'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password != $cpassword) {
        $_SESSION['error_password'] = TRUE;
    }

    if (!isset($_SESSION['error_password'])) {
        $db->query("UPDATE accounts SET password = '$password' WHERE account_id = $ID") OR die($db->error);
        $_SESSION['changed'] = TRUE;
        audit("Changed Admin Password");
    }
}
$user_data = $db->query("SELECT * FROM accounts WHERE account_id = $ID");
while ($data = mysqli_fetch_assoc($user_data)) {
    $fname = $data['firstname'];
    $lname = $data['lastname'];
    $email = $data['email'];
}
?>
<div class="row" >
    <div class="col-xs-4 col-xs-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3  class="text-danger"> Update Account </h3>

            </div>
            <div class="panel-body">
                <?php if (isset($_SESSION['updated'])) { ?>
                    <div class="alert alert-success alert-dismissable text-center"> 
                        Account Updated
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                    unset($_SESSION['updated']);
                }
                ?>
                <form method="post" action="account_settings.php">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="fname" class="form-control" required value="<?php echo $fname; ?>"/>
                    </div>
                    <div class="form-group ">
                        <label>Last Name</label>
                        <input type="text" name="lname" class="form-control" required value="<?php echo $lname; ?>"/>          
                    </div>           
                    <div class="form-group ">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo $email; ?>"/>          
                    </div>           
                    <div class="form-group">
                        <input type="submit" name="updateAcc" class="btn btn-success btn-block" value="Save"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3  class="text-danger"> Change Password</h3>
            </div>
            <div class="panel-body">
                <?php if (isset($_SESSION['error_password'])) { ?>
                    <div class="alert alert-danger alert-dismissable text-center"> 
                        Passwords Don't Match
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                    unset($_SESSION['error_password']);
                } elseif (isset($_SESSION['changed'])) {
                    ?>
                    <div class="alert alert-success text-center alert-dismissable"> 
                        Password Changed
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                    unset($_SESSION['changed']);
                }
                ?>
                <form method="post" action="account_settings.php">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" required />
                    </div>     
                    <div class="form-group ">
                        <input type="submit" name="changePass" class="btn btn-success btn-block" value="Change"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require "../includes/footer.php"; ?>
 



