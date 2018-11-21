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
$user_data = $db->query("SELECT * FROM accounts WHERE account_id = $ID");
while ($data = mysqli_fetch_assoc($user_data)) {
    $fname = $data['firstname'];
    $lname = $data['lastname'];
    $user = $data['username'];
    $email = $data['email'];
    $user_dir = $data['files_folder'];
}
$dir = scandir("../files/$user_dir");
$total_size = 0;
foreach ($dir as $value) {
    $total_size += filesize("../files/$user_dir/$value");
}
$storage_used = round($total_size / 1024 / 1024, 2);
$percentage_used = round(( ($total_size / 1024 / 1024) / 100) * 100, 2);
?>

<div class="row">
    <div class="col-xs-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="text-danger"> Upload File </h3>
            </div>
            <div class="panel-body">
                <form method="post" action="file_actions.php"  enctype='multipart/form-data'>
                    <div class="form-group">
                        <input type="file" id="img" name="fileupload" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">
                            <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>&nbsp;
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3  class="text-danger"> Profile </h3>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>Username</th>
                    <td>  <?php echo $user; ?></td>
                </tr>	
                <tr>
                    <th>First Name</th>
                    <td>  <?php echo $fname; ?></td>
                </tr>	
                <tr>
                    <th>Last Name</th>
                    <td>  <?php echo $lname; ?></td>
                </tr>		
                <tr>
                    <th>Email</th>
                    <td>  <?php echo $email; ?></td>
                </tr>		
            </table>
            <div class="panel-body">
                <label> Storage :  <?php echo $storage_used; ?> Mb of 100 Mb Used</label> 
                <div class="progress">
                    <?php
                    if ($percentage_used > 70) {
                        $percentage_color = "progress-bar-danger";
                    } elseif ($percentage_used > 50) {
                        $percentage_color = "progress-bar-warning";
                    } else {
                        $percentage_color = "progress-bar-success";
                    }
                    ?>
                    <div class="progress-bar <?php echo $percentage_color; ?>" style="width: <?php echo $percentage_used; ?>%;">
                        <?php echo $percentage_used; ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-9">
        <?php if (isset($_SESSION['file_upload_error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h3><strong><?php echo $_SESSION['file_upload_error']; ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h3>
            </div>
            <?php
            unset($_SESSION['file_upload_error']);
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3  class="text-danger"> Files </h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="search"> Search</label>
                    <input type="text"  class="form-control" id="search">
                </div>
            </div>
            <div class=" table-responsive" style=" height: 350px;">
                <table class="table table-bordered table-striped table-condensed">
                    <tr>
                        <th class="text-center"><b>File</b></th>
                        <th class="text-center"><b>Size</b></th>
                        <th class="text-center"><b>Date Uploded</b></th>
                        <th class="text-center" colspan="2"><b>Actions</b></th>
                    </tr>
                    <?php if (!empty($dir)) { ?>
                        <?php foreach ($dir as $value) { ?>
                            <tr>
                                <?php if ($value != ".." && $value != ".") { ?>
                                    <td>
                                        <?php echo $value; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $file_size = filesize("../files/$user_dir/$value");
                                        if ($file_size > 1000000) {
                                            echo round($file_size / 1024 / 1024, 2) . " MB";
                                        } else {
                                            echo round($file_size / 1024, 2) . " KB";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo date("M d,Y | h:i:s a", filectime("../files/$user_dir/$value")); ?>
                                    </td>
                                    <td>
                                        <a href='file_actions.php?dl_file=<?php echo $value; ?>' class="btn btn-primary btn-block btn-sm">
                                            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>&nbsp;
                                            Download
                                        </a> 
                                    </td>
                                    <td>
                                        <button onclick="deleteitem('<?php echo $value; ?>')"  class="btn btn-danger btn-block  btn-sm">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;
                                            Delete
                                        </button> 
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>    

<script>
    function deleteitem(file) {
        swal({
            title: "Delete File : " + file,
            text: "Once deleted, you will not be able to recover this file!",
            icon: "warning",
            buttons: {
                confirm: {
                    visible: true,
                    className: "btn btn-danger"
                },
                cancel: {
                    visible: true,
                    className: "btn btn-success"
                }
            }
        })
                .then((willDelete) => {
                    if (willDelete) {
                        location.href = 'file_actions.php?del_file=' + file;
                    } else {
                        swal({
                            title: "File Deletation Canceled",
                            text: " ",
                            buttons: {
                                confirm: {
                                    visible: true,
                                    className: "btn btn-success"
                                }
                            }
                        });
                    }
                });
    }

    search.onkeyup = function () {
        var search_val = this.value.toLowerCase();
        $(".table-condensed tr:not(tr:first-child)").each(function () {
            this.style.visibility = "collapse";
        });
        $(".table-condensed tr td:first-child").each(function () {
            if (RegExp(search_val).test(this.innerHTML.toLowerCase())) {
                $(this).parent('tr').css("visibility", "visible");
            }
        });
        if (!search_val) {
            $(".table-condensed tr:not(tr:first-child)").each(function () {
                this.style.visibility = "visible";
            });
        }
    };
</script>
<?php require "../includes/footer.php"; ?>
