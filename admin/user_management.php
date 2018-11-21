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
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = strtolower($search);
    $user_data = $db->query("SELECT * FROM accounts WHERE account_id != $ID AND (firstname LIKE '%$search%' OR lastname LIKE '%$search%')");
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3><span class="text-danger">User Management</span></h3>
            </div>
            <div class="panel-body">
                <form action="user_management.php" method="POST" class="row">
                    <div class="col-xs-2">
                        <a href="account_create.php" class="btn btn-success">Create Account</a> 
                    </div>
                    <div class="form-group col-xs-7">
                        <input type="text" class="form-control" name="search">
                    </div>
                    <div class="form-group col-xs-2">
                        <button type="submit" class="btn btn-danger"> Search </button>
                    </div>
                </form>
            </div>
            <table class="table table-bordered table-striped table-condensed">
                <tr>
                    <th>Account ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th colspan="10">Action</th>
                </tr>
                <?php
                if (isset($user_data)) {
                    while ($data = mysqli_fetch_assoc($user_data)) {
                        ?>
                        <tr>
                            <td><?php echo $data['account_id']; ?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['firstname']; ?></td>
                            <td><?php echo $data['lastname']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['type'] == 1 ? "Admin" : "User"; ?></td>
                            <td> 
                                <a href="account_status.php?id=<?php echo $data['account_id']; ?>&s=<?php echo $data['acc_status'] == 1 ? 0 : 1; ?>" class="btn btn-danger btn-sm">Deactivated</a>
                            </td>
                        </tr>	
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
<?php require "../includes/footer.php"; ?>
