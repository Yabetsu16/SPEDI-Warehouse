<?php require "../config/connectdb.php";
session_start();

if (isset($_SESSION['id'])) {
    $accont_id = $_SESSION['id'];
    $username = $_SESSION['username'];
} else {
    // Redirect them to the login page
    header("Location: ../");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SPEDI Warehouse Control</title>
    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="../dist/css/mdb.min.css">
    <!-- MDBootstrap Datatables  -->
    <link href="../dist/css/addons/datatables.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="../dist/css/style.css">
</head>

<body>

    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark elegant-color">

        <!-- Navbar brand -->
        <a class="navbar-brand" href="index.php">SPEDI Warehouse Control - Superadmin</a>

        <!-- Collapse button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#spediNavBar" aria-controls="spediNavBar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="spediNavBar">

            <!-- Links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="project_office_management.php">Project / Office Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="item_management.php">Item Management</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="user_management.php">User Management</a>
                    <span class="sr-only">(current)</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="summary.php">Summary</a>
                </li>
            </ul>
            <!-- Links -->

            <ul class="navbar-nav ml-auto nav-flex-icons">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-capitalize" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $username ?> <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Collapsible content -->
    </nav>
    <!--/.Navbar-->

    <?php
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        $query = "SELECT * FROM account_tb WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                User duplicate.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        } else {
            $query = "INSERT INTO account_tb (username, password, user_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $username, $password, $user_type);
            if ($stmt->execute()) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    User <?php echo $username ?> added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    if (isset($_POST['edit_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $update_account_id = $_POST['update_account_id'];
        $user_type = $_POST['user_type'];

        $query = "SELECT * FROM account_tb WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                User duplicate.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        } else {
            $query = "UPDATE account_tb SET username = ?, password = ?, user_type = ? WHERE account_id = $update_account_id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $username, $password, $user_type);
            if ($stmt->execute()) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    User <?php echo $username ?> updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    if (isset($_POST['delete_user'])) {
        $username = $_POST['username'];
        $delete_account_id = $_POST['delete_account_id'];

        $query = "DELETE FROM account_tb WHERE account_id = $delete_account_id";
        $stmt = $conn->prepare($query);
        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User <?php echo $username ?> deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>

    <!-- Management -->
    <section id="management" class="mt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header elegant-color text-white text-center">
                            <h1 class="h1-responsive">User Management</h1>
                        </div>
                        <div class="card-body">
                            <div class="float-right">
                                <br>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUserModal">Add
                                    User</button>
                            </div>
                            <br>
                            <table id="dtBasicExample" class="table table-striped table-responsive-sm btn-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="th-sm">Actions
                                        </th>
                                        <th class="th-sm">Username
                                        </th>
                                        <th class="th-sm">Password
                                        </th>
                                        <th class="th-sm">Type
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SELECT all from inventory table
                                    $query = "SELECT * FROM account_tb";

                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Display data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $account_id = $row['account_id'];
                                            $username = $row['username'];
                                            $password = $row['password'];
                                            $user_type = $row['user_type'];

                                            if ($user_type == 1) {
                                                $user_type = "Superadmin";
                                            } else if ($user_type == 2) {
                                                $user_type = "Admin";
                                            } else if ($user_type == 3) {
                                                $user_type = "Materials Control";
                                            }
                                    ?>
                                            <tr class="text-center">
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editUserModal<?= $account_id ?>">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalUserConfirmDelete<?= $account_id ?>">Delete</button>
                                                </td>
                                                <td><?= $username ?></td>
                                                <td><?= $password ?></td>
                                                <td><?= $user_type ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th class="th-sm">Actions
                                        </th>
                                        <th class="th-sm">Username
                                        </th>
                                        <th class="th-sm">Password
                                        </th>
                                        <th class="th-sm">Type
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <br>
    <br>
    <br>
    <!-- /.Management -->


    <!-- Modal Add User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addItemModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h1 class="modal-title">Add User</h1>
                </div>
                <div class="modal-body mt-3">
                    <!-- Material form grid -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-- Grid row -->
                        <div class="row">
                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="username" class="form-control validate" id="username" required>
                                    <label for="username" data-error="wrong" data-success="right">Username</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="password" name="password" class="form-control validate" id="password" required>
                                    <label for="password" data-error="wrong" data-success="right">Password</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <select id="user_type" name="user_type" class="browser-default custom-select">
                                        <option selected>Select User Type</option>
                                        <option value="1">Superadmin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Materials Control</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Grid column -->
                        </div>
                        <!-- Grid row -->
                        <div class="text-center">
                            <button type="submit" name="add_user" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                    <!-- Material form grid -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal Add User -->

    <?php
    // SELECT all from account table
    $query = "SELECT * FROM account_tb";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Display data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $manage_account_id = $row['account_id'];
            $username = $row['username'];
            $password = $row['password'];
            $user_type = $row['user_type'];
            //$password = str_repeat("*", strlen($password));
    ?>
            <!-- Modal Edit User <?php echo $manage_account_id ?> -->
            <div class="modal fade" id="editUserModal<?php echo $manage_account_id ?>" tabindex="-1" role="dialog" aria-labelledby="editItemModal<?php echo $manage_account_id ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header elegant-color text-white d-flex justify-content-center text-center">
                            <h1 class="modal-title">Edit <?php echo $username ?> User</h1>
                        </div>
                        <div class="modal-body">
                            <!-- Material form grid -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!-- Grid row -->
                                <div class="row">
                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="username" class="form-control validate" id="username" value="<?php echo $username ?>" required>
                                            <label for="username" data-error="wrong" data-success="right">Username</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="password" name="password" class="form-control validate" id="password" value="<?php echo $password ?>" required>
                                            <label for="password" data-error="wrong" data-success="right">Password</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <select id="user_type" name="user_type" class="browser-default custom-select">
                                                <?php
                                                if ($user_type == 1) { ?>
                                                    <option value="1" selected>Current: Superadmin</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Materials Control</option>
                                                <?php
                                                } else if ($user_type == 2) { ?>
                                                    <option value="2" selected>Current: Admin</option>
                                                    <option value="1">Superadmin</option>
                                                    <option value="3">Materials Control</option>
                                                <?php
                                                } else if ($user_type == 3) { ?>
                                                    <option value="3" selected>Current: Materials Control</option>
                                                    <option value="1">Superadmin</option>
                                                    <option value="2">Admin</option>
                                                <?php
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <!-- Grid column -->
                                </div>
                                <!-- Grid row -->
                                <div class="text-center">
                                    <input type="hidden" name="update_account_id" value="<?php echo $manage_account_id ?>">
                                    <button type="submit" name="edit_user" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                            <!-- Material form grid -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.Modal Edit User <?php echo $manage_account_id ?> -->

            <!-- Modal User Confirm Delete <?php echo $manage_account_id ?> -->
            <div class="modal fade" id="modalUserConfirmDelete<?php echo $manage_account_id ?>" tabindex="-1" role="dialog" aria-labelledby="modalUserConfirmDelete<?php echo $manage_account_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-md modal-notify modal-danger" role="document">
                    <!--Content-->
                    <div class="modal-content text-center">
                        <!--Header-->
                        <div class="modal-header d-flex justify-content-center">
                            <p class="heading">Are you sure?</p>
                        </div>

                        <!--Body-->
                        <div class="modal-body">
                            <h4>Do you want to delete <?php echo $username ?>?</h4>
                            <i class="fas fa-times fa-4x animated rotateIn"></i>

                        </div>

                        <!--Footer-->
                        <div class="modal-footer flex-center">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="username" value="<?php echo $username ?>">
                                <input type="hidden" name="delete_account_id" value="<?php echo $manage_account_id ?>">
                                <button type="submit" class="btn btn-danger" name="delete_user">Yes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
            <!-- Modal User Confirm Delete <?php echo $manage_account_id ?> -->
    <?php
        }
    }
    ?>


    <!-- Footer -->
    <footer class="page-footer font-small elegant-color pt-4 mt-3 fixed-bottom">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="index.html">SPEDI Warehouse Control</a>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->

    <!-- jQuery -->
    <script type="text/javascript" src="../dist/js/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="../dist/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="../dist/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="../dist/js/mdb.min.js"></script>
    <!-- MDBootstrap Datatables  -->
    <script type="text/javascript" src="../dist/js/addons/datatables.min.js"></script>
    <!-- Your custom scripts (optional) -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dtBasicExample').DataTable({
                "lengthMenu": [
                    [5, 10, 20, -1],
                    [5, 10, 20, "All"]
                ]
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dtUserManagement').DataTable({
                "lengthMenu": [
                    [5, 10, 20, -1],
                    [5, 10, 20, "All"]
                ]
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

</body>

</html>