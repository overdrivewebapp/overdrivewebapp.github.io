<?php
require "../includes/dbconnections.php";
require "../includes/header.php";

if (!isset($_SESSION['id'])) {
    header("location: ../index.php");
}
if ($_SESSION['type'] != 2) {
    header("location: ../logout.php");
}

$ID = $_SESSION['id'];
$user_data = $db->query("SELECT * FROM audit_trail WHERE account_id = $ID ORDER BY audit_id DESC ");
?>

<div class="row">

    <div class="col-xs-8 col-xs-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3><span class="text-danger">User Logs</span></h3>
            </div>
            <div class="table-responsive" style="max-height: 450px !important;">
                <table class="table table-bordered table-striped table-condensed">
                    <tr>
                        <th>Action Description</th>
                        <th>Date / Time</th>
                    </tr>
                    <?php
                    if (isset($user_data)) {
                        while ($data = mysqli_fetch_assoc($user_data)) {
                            ?>
                            <tr>
                                <td>  <?php echo $data['description']; ?></td>
                                <td>  <?php echo date("M j, Y | h:i A", $data['audit_date']); ?></td>
                            </tr>	
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require "../includes/footer.php"; ?>
