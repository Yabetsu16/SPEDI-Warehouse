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
                <li class="nav-item active">
                    <a class="nav-link" href="project_office_management.php">Project / Office Management
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="item_management.php">Item Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_management.php">User Management</a>
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
    if (isset($_POST['add_project'])) {
        $project_office_name = $_POST['project_office_name'];
        $location = $_POST['location'];

        $query = "SELECT * FROM project_office_tb WHERE project_office_name = ? AND location = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $project_office_name, $location);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Project <strong><?php echo $project_office_name ?> </strong> has duplicate entry.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php    } else {
            $query = "INSERT INTO project_office_tb(project_office_name, location) 
                VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "ss",
                $project_office_name,
                $location
            );
            if ($stmt->execute()) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $project_office_name ?> Added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Failed to Add <?php echo $project_office_name ?> <br>
                    <?php echo $conn->error ?>
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
    if (isset($_POST['edit_project'])) {
        $project_id = $_POST['project_id'];
        $project_office_name = $_POST['edit_project_name'];
        $location = $_POST['edit_location'];

        $query = "UPDATE project_office_tb SET project_office_name = ?,
        location = ? WHERE project_id = ?";
        $stmt = $conn->prepare($query);

        $stmt->bind_param(
            "ssi",
            $project_office_name,
            $location,
            $project_id
        );
        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $project_office_name ?> Editted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to Edit <?php echo $project_office_name ?> <br>
                <?php echo $conn->error ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>

    <?php
    if (isset($_POST['delete_project'])) {
        $project_id = $_POST['project_id'];
        $project_office_name = $_POST['project_office_name'];

        $query = "DELETE FROM project_office_tb WHERE project_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $project_id);
        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $project_office_name ?> Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to Edit <?php echo $project_office_name ?> <br>
                <?php echo $conn->error ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>

    <?php
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

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
            $query = "INSERT INTO account_tb (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $password);
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

        $query = "UPDATE account_tb SET username = ?, password = ? WHERE account_id = $update_account_id";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
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
                            <h1 class="h1-responsive">Project / Office Management</h1>
                        </div>
                        <div class="card-body">
                            <div class="float-right">
                                <br>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProjectModal">Add
                                    project / office</button>
                            </div>
                            <br>
                            <table id="dtBasicExample" class="table table-striped table-responsive-sm btn-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="th-sm">Actions
                                        </th>
                                        <th class="th-sm">Project / Office
                                        </th>
                                        <th class="th-sm">Location
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SELECT all from inventory table
                                    $query = "SELECT * FROM project_office_tb";

                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Display data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $project_id = $row['project_id'];
                                            $project_office_name = $row['project_office_name'];
                                            $location = $row['location'];
                                    ?>
                                            <tr class="text-center">
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editProjectModal<?php echo $project_id ?>">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalConfirmDelete<?php echo $project_id ?>">Delete</button>
                                                </td>
                                                <td><?php echo $project_office_name ?></td>
                                                <td><?php echo $location ?></td>
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
                                        <th class="th-sm">Project / Office
                                        </th>
                                        <th class="th-sm">Location
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

    <!-- Modal Add Item -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h1 class="modal-title">Add Project / Office</h1>
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
                                    <input type="text" name="project_office_name" class="form-control validate" id="project_office_name" required>
                                    <label for="project_office_name" data-error="wrong" data-success="right">Project
                                        / Office Name</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="location" class="form-control validate" id="location" required>
                                    <label for="location" data-error="wrong" data-success="right">Location</label>
                                </div>
                            </div>
                            <!-- Grid column -->
                        </div>
                        <!-- Grid row -->
                        <div class="text-center">
                            <button type="submit" name="add_project" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                    <!-- Material form grid -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal Add Item -->
    <?php
    // SELECT all from inventory table
    $query = "SELECT * FROM project_office_tb";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Display data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $project_id = $row['project_id'];
            $project_office_name = $row['project_office_name'];
            $location = $row['location'];
    ?>
            <!-- Modal Edit Item <?php echo $project_id ?> -->
            <div class="modal fade" id="editProjectModal<?php echo $project_id ?>" tabindex="-1" role="dialog" aria-labelledby="editProjectModal<?php echo $project_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header elegant-color text-white d-flex justify-content-center">
                            <h1 class="modal-title">Edit <?php echo $project_office_name ?> Item</h1>
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
                                            <input type="text" name="edit_project_name" class="form-control validate" id="edit_project_name" value="<?php echo $project_office_name ?>" required>
                                            <label for="edit_project_name" data-error="wrong" data-success="right">Project / Office</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_location" class="form-control validate" id="edit_location" value="<?php echo $location ?>" required>
                                            <label for="edit_location" data-error="wrong" data-success="right">Location
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->
                                </div>
                                <!-- Grid row -->
                                <div class="text-center">
                                    <input type="hidden" name="project_id" value="<?php echo $project_id ?>">
                                    <button type="submit" name="edit_project" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                            <!-- Material form grid -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.Modal Edit Item <?php echo $project_id ?> -->

            <!-- Modal Confirm Delete <?php echo $project_id ?> -->
            <div class="modal fade" id="modalConfirmDelete<?php echo $project_id ?>" tabindex="-1" role="dialog" aria-labelledby="modalConfirmDelete<?php echo $project_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-md modal-notify modal-danger" role="document">
                    <!--Content-->
                    <div class="modal-content text-center">
                        <!--Header-->
                        <div class="modal-header d-flex justify-content-center">
                            <p class="heading">Are you sure?</p>
                        </div>

                        <!--Body-->
                        <div class="modal-body">
                            <h4>Do you want to delete Project <?php echo $project_office_name ?>?</h4>
                            <i class="fas fa-times fa-4x animated rotateIn"></i>

                        </div>

                        <!--Footer-->
                        <div class="modal-footer flex-center">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="project_office_name" value="<?php echo $project_office_name ?>">
                                <input type="hidden" name="project_id" value="<?php echo $project_id ?>">
                                <button type="submit" class="btn btn-danger" name="delete_project">Yes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
            <!-- Modal Confirm Delete <?php echo $project_id ?> -->
    <?php
        }
    }
    ?>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fluid" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h1 class="modal-title">User Management</h1>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-right">
                                <br>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUserModal">Add
                                    user</button>
                            </div>
                            <br>
                            <table id="dtUserManagement" class="table table-striped table-responsive-lg btn-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="th-sm">Actions
                                        </th>
                                        <th class="th-sm">Username
                                        </th>
                                        <th class="th-sm">Password
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
                                            $accont_id = $row['account_id'];
                                            $username = $row['username'];
                                            $password = $row['password'];
                                            //$password = str_repeat("*", strlen($password));
                                    ?>
                                            <tr class="text-center">
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editUserModal<?php echo $accont_id ?>">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalUserConfirmDelete<?php echo $accont_id ?>">Delete</button>
                                                </td>
                                                <td><?php echo $username ?></td>
                                                <td><?php echo $password ?></td>
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
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- User Modal -->

    <!-- Modal Add User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addItemModal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h1 class="modal-title">Add User</h1>
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
            //$password = str_repeat("*", strlen($password));
    ?>
            <!-- Modal Edit User <?php echo $manage_account_id ?> -->
            <div class="modal fade" id="editUserModal<?php echo $manage_account_id ?>" tabindex="-1" role="dialog" aria-labelledby="editItemModal<?php echo $manage_account_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
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