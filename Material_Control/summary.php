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
        <a class="navbar-brand" href="index.php">SPEDI Warehouse Control - Materials Control</a>

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
                    <a class="nav-link" href="project_office_management.php">Project / Office Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="item_management.php">Item Management </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="summary.php">Summary
                        <span class="sr-only">(current)</span>
                    </a>
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
    if (isset($_POST['export_type'])) {
        $item_type = $_POST['item_type'];
        $project_id = $_POST['project_id'];

        if ($item_type == "All") {
            header("Location: export_all.php?p=$project_id");
        } else if ($item_type == "") {
            header("Location: export_materials.php?p=$project_id");
        } else if ($item_type == "Tools") {
            header("Location: export_tools.php?p=$project_id");
        } else if ($item_type == "Safety") {
            header("Location: export_safety.php?p=$project_id");
        }
    }
    ?>

    <!-- Summary -->
    <section id="summary" class="mt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header elegant-color text-white text-center">
                            <h1 class="h1-responsive">Summary</h1>
                        </div>
                        <div class="card-body">
                            <div class="float-right">
                                <br>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exportModal">Export to Excel</button>
                            </div>
                            <br>
                            <table id="dtBasicExample" class="table table-striped table-responsive-md btn-table" cellspacing="0" width="100%" data-ordering="false">
                                <thead>
                                    <tr class="text-center">
                                        <th class="th-sm">Timestamp
                                        </th>
                                        <th class="th-sm">Project / Office
                                        </th>
                                        <th class="th-sm">Item Type
                                        </th>
                                        <th class="th-sm">Item Name
                                        </th>
                                        <th class="th-sm">Unit
                                        </th>
                                        <th class="th-sm">Quantity
                                        </th>
                                        <th class="th-sm">Issued
                                        </th>
                                        <th class="th-sm">Issued to Project / Office
                                        </th>
                                        <th class="th-sm">Returned
                                        </th>
                                        <th class="th-sm">Balance
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM movement_tb 
                                    INNER JOIN inventory_tb ON inventory_tb.inventory_id = movement_tb.inventory_id
                                    INNER JOIN project_office_tb ON project_office_tb.project_id = inventory_tb.project_id
                                    ORDER BY inventory_tb.inventory_id;";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        $project_office_name = $row['project_office_name'];
                                        $item_type = $row['item_type'];
                                        $item_name = $row['item_name'];
                                        $unit = $row['unit'];
                                        $quantity = $row['quantity'];
                                        $issued = $row['issued'];
                                        $returned = $row['returned'];
                                        $balance = $row['balance'];
                                        $date_movement = $row['date_movement'];
                                        $converted_date_movement = date_create($date_movement);
                                        $to_project_office = $row['to_project_office'];
                                    ?>
                                        <tr class="text-center">
                                            <td><?php echo date_format($converted_date_movement, "Y-m-d h:i A") ?></td>
                                            <td><?php echo $project_office_name ?></td>
                                            <td><?php echo $item_type ?></td>
                                            <td><?php echo $item_name ?></td>
                                            <td><?php echo $unit ?></td>
                                            <td><?php echo $quantity ?></td>
                                            <td><?php echo $issued ?></td>
                                            <td><?php echo $to_project_office ?></td>
                                            <td><?php echo $returned ?></td>
                                            <td><?php echo $balance ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th class="th-sm">Timestamp
                                        </th>
                                        <th class="th-sm">Project / Office
                                        </th>
                                        <th class="th-sm">Item Type
                                        </th>
                                        <th class="th-sm">Item Name
                                        </th>
                                        <th class="th-sm">Unit
                                        </th>
                                        <th class="th-sm">Quantity
                                        </th>
                                        <th class="th-sm">Issued
                                        </th>
                                        <th class="th-sm">Returned
                                        </th>
                                        <th class="th-sm">Balance
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
    <!-- /.Summary -->

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h3 class="modal-title h4-responsive">Select Project / Office and Item Type</h4>
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
                                    <select name="item_type" class="browser-default custom-select">
                                        <option selected>Select Type</option>
                                        <option value="All">All</option>
                                        <option value="Materials">Materials</option>
                                        <option value="Tools">Tools</option>
                                        <option value="Safety">Safety</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Grid column -->
                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <select name="project_id" class="browser-default custom-select">
                                        <option value="" selected>Select Project / Office</option>
                                        <option value="All">All</option>
                                        <?php
                                        $query = "SELECT * FROM project_office_tb";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $project_id = $row['project_id'];
                                            $project_office_name = $row['project_office_name'];
                                            $location = $row['location'];
                                        ?>
                                            <option value="<?php echo $project_id ?>"><?php echo $project_office_name ?> in <?php echo $location ?></option>
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
                            <button type="submit" name="export_type" class="btn btn-primary">Export</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                    <!-- Material form grid -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.Export Modal -->

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