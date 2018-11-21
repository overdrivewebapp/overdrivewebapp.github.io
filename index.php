<?php
require "includes/dbconnections.php";
require "includes/header.php";
$error = 0;
if (isset($_SESSION['id'])) {
    if ($_SESSION['type'] == 1) {
        header("location: admin/user_management.php");
    } elseif ($_SESSION['type'] == 2) {
        header("location: user/file_manager.php");
    }
}
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $account = $db->query("SELECT * FROM accounts WHERE username = '$username' AND password = '$password'");
    if ($account->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($account)) {
            $_SESSION['type'] = $data['type'];
            $_SESSION['id'] = $data['account_id'];
            $acc_stat = $data['acc_status'];
        }
        if ($acc_stat != 0) {
            if ($_SESSION['type'] == 1) {
                audit("Admin Login");
                header("location: admin/user_management.php");
            } elseif ($_SESSION['type'] == 2) {
                audit("User Login");
                header("location: user/file_manager.php");
            }
        } else {
            $error = 2;
        }
    } else {
        $error = 1;
    }
}
?>
<div class="row" >
    <div class=" col-xs-4 col-xs-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-center"><span class="text-danger">OverDrive</span>  </h2>
            </div>
            <ul class="list-group ">
                <li class="list-group-item text-danger text-center text-uppercase">Free online file management</li>
                <li class="list-group-item text-danger text-center text-uppercase">Upload your files anywhere</li>
                <li class="list-group-item text-danger text-center text-uppercase">Manage your files anywhere</li>
                <li class="list-group-item">
                    <a href="registration.php" class="btn btn-danger btn-sm btn-block" > Join Now!</a>
                </li>
            </ul>
            <div class="panel-body">
                <?php if ($error == 1) { ?>
                    <div class="alert alert-danger"> 
                        Invalid Username/Password
                    </div>
                <?php } elseif ($error == 2) { ?>
                    <div class="alert alert-danger"> 
                        Account Blocked
                    </div>
                <?php } ?>
                <form method="post" action="index.php">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn btn-success btn-block" name="LOGIN"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>


