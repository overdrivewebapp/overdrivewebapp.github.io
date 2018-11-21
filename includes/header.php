<html>
    <head>
        <title>OverDrive</title>
       <link rel="stylesheet" href="<?php echo isset($_SESSION['id']) ? "../" : "" ?>bootstrap/css/bootstrap.css">
       <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" ></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body class="container-fluid" style="background-color: black;">
        <br><br>
        <?php if (isset($_SESSION['id'])) { ?>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <a class="navbar-brand" ><span class="text-danger">OverDrive</span></a>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php if ($_SESSION['type'] == 1) { ?>
                                <li <?php echo file_name() == "user_management" ? "class='active'" : "" ?>>
                                    <a href="user_management.php">
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
                                            User Management
                                        </span>
                                    </a>
                                </li>
                                <li <?php echo file_name() == "audit_trail" ? "class='active'" : "" ?>>
                                    <a href="audit_trail.php">
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;
                                            Audit Trail
                                        </span>
                                    </a>
                                </li>
                                <li <?php echo file_name() == "account_settings" ? "class='active'" : "" ?>>
                                    <a href="account_settings.php" >
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp;
                                            Settings
                                        </span>
                                    </a>
                                </li> 
                            <?php } elseif ($_SESSION['type'] == 2) { ?>
                                <li <?php echo file_name() == "file_manager" ? "class='active'" : "" ?>>
                                    <a href="file_manager.php" >
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;
                                            Files
                                        </span>
                                    </a>
                                </li>
                                <li <?php echo file_name() == "audit_trail" ? "class='active'" : "" ?>>
                                    <a href="audit_trail.php">
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;
                                            User Logs
                                        </span>
                                    </a>
                                </li>
                                                                <li <?php echo file_name() == "account_settings" ? "class='active'" : "" ?>>
                                    <a href="account_settings.php" >
                                        <span class="text-danger">
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp;
                                            Settings
                                        </span>
                                    </a>
                                </li> 
                            <?php } ?>
                            <li>
                                <a href="../logout.php">
                                    <span class="text-danger">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;
                                        Logout
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br><br>
        <?php } ?>