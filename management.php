<?php require "config/connectdb.php"; ?>
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
    <link rel="stylesheet" href="dist/css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="dist/css/mdb.min.css">
    <!-- MDBootstrap Datatables  -->
    <link href="dist/css/addons/datatables.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="dist/css/style.css">
</head>

<body>

    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark elegant-color">

        <!-- Navbar brand -->
        <a class="navbar-brand" href="index.php">Warehouse Control</a>

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
                    <a class="nav-link" href="management.php">Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="summary.php">Summary</a>
                </li>
            </ul>
            <!-- Links -->
        </div>
        <!-- Collapsible content -->
    </nav>
    <!--/.Navbar-->

    <?php
    if (isset($_POST['add_item'])) {
        $item_type = $_POST['item_type'];
        $project_name = $_POST['project_name'];
        $item_name = $_POST['item_name'];
        $item_description = $_POST['item_description'];
        $unit = $_POST['unit'];
        $unit_cost = $_POST['unit_cost'];
        $remarks = $_POST['remarks'];

        $query = "SELECT * FROM inventory_tb WHERE item_type = ? AND item_name = ? 
            AND item_description = ? AND project_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $item_type, $item_name, $item_description, $project_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Item <strong><?php echo $item_name . " - " . $item_type ?> </strong> has duplicate entry.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php    } else {
            $query = "INSERT INTO inventory_tb(item_type, item_name, 
                item_description, unit, unit_cost, project_name, remarks) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "ssssdss",
                $item_type,
                $item_name,
                $item_description,
                $unit,
                $unit_cost,
                $project_name,
                $remarks
            );
            if ($stmt->execute()) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $item_name ?> Added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Failed to Add <?php echo $item_name ?> <br>
                    <?php echo $conn->error ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    <?php
            }

            $query = "INSERT INTO count_tb (inventory_id) VALUES 
                ((SELECT inventory_id FROM inventory_tb WHERE inventory_id = LAST_INSERT_ID()));";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $query = "INSERT INTO recent_tb (inventory_id) VALUES 
                ((SELECT inventory_id FROM inventory_tb WHERE inventory_id = LAST_INSERT_ID()));";
            $stmt = $conn->prepare($query);
            $stmt->execute();
        }
    }
    ?>

    <?php
    if (isset($_POST['count_submit'])) {
        $inventory_id = $_POST['inventory_id'];
        $item_name = $_POST['item_name'];
        $count_id = $_POST['count_id'];
        $quantity = $_POST['quantity'];
        $issued = $_POST['issued'];
        $returned = $_POST['returned'];
        $date_added = $_POST['date_added'];
        $date_issued = $_POST['date_issued'];
        $date_returned = $_POST['date_returned'];
        $balance = $quantity - $issued + $returned;

        $query = "UPDATE count_tb SET quantity = ?, issued = ?, returned = ?, 
        date_added = ?, date_issued = ?, date_returned = ? WHERE inventory_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisssi", $quantity, $issued, $returned, $date_added, $date_issued, $date_returned, $inventory_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $query = "INSERT INTO movement_tb (inventory_id, quantity, issued, returned, balance,
        date_added, date_issued, date_returned) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "iiiiisss",
            $inventory_id,
            $quantity,
            $issued,
            $returned,
            $balance,
            $date_added,
            $date_issued,
            $date_returned
        );

        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $item_name ?> Count Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to Update <?php echo $item_name ?> Count <br>
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
    if (isset($_POST['edit_item'])) {
        $inventory_id = $_POST['inventory_id'];
        $item_type = $_POST['edit_item_type'];
        $project_name = $_POST['edit_project_name'];
        $item_name = $_POST['edit_item_name'];
        $item_description = $_POST['edit_item_description'];
        $unit = $_POST['edit_unit'];
        $unit_cost = $_POST['edit_unit_cost'];
        $remarks = $_POST['edit_remarks'];

        $query = "UPDATE inventory_tb SET item_type = ?, item_name = ?, project_name = ?,
        item_description = ?, unit = ?, unit_cost = ?, remarks = ?
        WHERE inventory_id = ?";
        $stmt = $conn->prepare($query);

        $stmt->bind_param(
            "sssssdsi",
            $item_type,
            $item_name,
            $project_name,
            $item_description,
            $unit,
            $unit_cost,
            $remarks,
            $inventory_id
        );
        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $item_name ?> Editted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to Edit <?php echo $item_name ?> <br>
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
    if (isset($_POST['delete_item'])) {
        $inventory_id = $_POST['inventory_id'];
        $item_name = $_POST['item_name'];

        $query = "DELETE FROM inventory_tb WHERE inventory_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $inventory_id);
        if ($stmt->execute()) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $item_name ?> Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to Edit <?php echo $item_name ?> <br>
                <?php echo $conn->error ?>
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
                            <h1 class="h1-responsive">Management</h1>
                        </div>
                        <div class="card-body">
                            <div class="float-right">
                                <br>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addItemModal">Add
                                    item</button>
                            </div>
                            <br>
                            <table id="dtBasicExample" class="table table-striped table-responsive-md btn-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="th-sm">Actions
                                        </th>
                                        <th class="th-sm">Project Name
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
                                        <th class="th-sm">
                                        </th>
                                        <th class="th-sm">Balance
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SELECT all from inventory table
                                    $query = "SELECT inventory_tb.*, count_tb.count_id, count_tb.quantity, 
                                    count_tb.issued, count_tb.returned, count_tb.date_added, 
                                    count_tb.date_issued, count_tb.date_returned 
                                    FROM inventory_tb INNER JOIN count_tb 
                                        ON count_tb.inventory_id = inventory_tb.inventory_id;";

                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Display data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id = $row['inventory_id'];
                                            $count_id = $row['count_id'];
                                            $item_type = $row['item_type'];
                                            $item_name = $row['item_name'];
                                            $item_description = $row['item_description'];
                                            $unit = $row['unit'];
                                            $unit_cost = $row['unit_cost'];
                                            $project_name = $row['project_name'];
                                            $remarks = $row['remarks'];
                                            $date_added = $row['date_added'];
                                            $quantity = $row['quantity'];
                                            $issued = $row['issued'];
                                            $date_issued = $row['date_issued'];
                                            $returned = $row['returned'];
                                            $date_returned = $row['date_returned'];
                                            $balance = $quantity - $issued + $returned;
                                    ?>
                                            <tr class="text-center">
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#EditItemModal<?php echo $id ?>">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#ModalConfirmDelete<?php echo $id ?>">Delete</button>
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#DetailsModal<?php echo $id ?>">Details</button>
                                                    <button type="button" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#MovementModal<?php echo $id ?>">Movement</button>
                                                </td>
                                                <td><?php echo $project_name ?></td>
                                                <td><?php echo $item_type ?></td>
                                                <td><?php echo $item_name ?></td>
                                                <td><?php echo $unit ?></td>
                                                <form action="#" method="post">
                                                    <input type="hidden" name="item_name" value="<?php echo $item_name ?>">
                                                    <input type="hidden" name="inventory_id" value="<?php echo $id ?>">
                                                    <input type="hidden" name="count_id" value="<?php echo $count_id ?>">
                                                    <input type="hidden" name="balance" value="<?php echo $balance ?>">
                                                    <td>
                                                        <div class="md-form input-group">
                                                            <input type="number" name="quantity" min="0" value="<?php echo $balance ?>" class="form-control text-center">
                                                        </div>
                                                        <div class="md-form input-group">
                                                            <input type="date" name="date_added" class="form-control text-center" id="date_added">
                                                            <label for="date_added">Date Added</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="md-form input-group">
                                                            <input type="number" name="issued" min="0" value="0" class="form-control text-center">
                                                        </div>
                                                        <div class="md-form input-group">
                                                            <input type="date" name="date_issued" class="form-control text-center" id="date_issued">
                                                            <label for="date_issued">Date Issued</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="md-form input-group">
                                                            <input type="number" name="returned" min="0" value="0" class="form-control text-center">
                                                        </div>
                                                        <div class="md-form input-group">
                                                            <input type="date" name="date_returned" class="form-control text-center" id="date_returned">
                                                            <label for="date_returned">Date Returned</label>
                                                        </div>
                                                    </td>
                                                    <td><button type="submit" name="count_submit" class="btn btn-sm btn-primary">Submit</button></td>
                                                </form>
                                                <td><?php echo $balance ?></td>
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
                                        <th class="th-sm">Project Name
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
                                        <th class="th-sm">
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
    <!-- /.Management -->

    <!-- Modal Add Item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header elegant-color text-white d-flex justify-content-center">
                    <h1 class="modal-title">Add Item</h1>
                </div>
                <div class="modal-body">
                    <!-- Material form grid -->
                    <form action="#" method="post">
                        <!-- Grid row -->
                        <div class="row">
                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <select name="item_type" class="browser-default custom-select">
                                        <option selected>Select Item Type</option>
                                        <option value="Materials">Materials</option>
                                        <option value="Tools">Tools</option>
                                        <option value="Safety">Safety</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-6">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="project_name" class="form-control validate" id="project_name" required>
                                    <label for="project_name" data-error="wrong" data-success="right">Project
                                        Name</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-6">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="item_name" class="form-control validate" id="item_name" required>
                                    <label for="item_name" data-error="wrong" data-success="right">Item Name</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="item_description" class="form-control validate" id="item_description" required>
                                    <label for="item_description" data-error="wrong" data-success="right">Item
                                        Description</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-6">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="unit" class="form-control validate" id="unit" required>
                                    <label for="unit" data-error="wrong" data-success="right">Unit</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-6">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="number" name="unit_cost" class="form-control validate" id="unit_cost" required step="0.01" min="0">
                                    <label for="unit_cost" data-error="wrong" data-success="right">Unit Cost</label>
                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-12">
                                <!-- Material input -->
                                <div class="md-form mt-0">
                                    <input type="text" name="remarks" class="form-control validate" id="remarks" required>
                                    <label for="remarks" data-error="wrong" data-success="right">Remarks</label>
                                </div>
                            </div>
                            <!-- Grid column -->
                        </div>
                        <!-- Grid row -->
                        <div class="text-center">
                            <button type="submit" name="add_item" class="btn btn-primary">Add</button>
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
    $query = "SELECT * FROM inventory_tb";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Display data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $inventory_id = $row['inventory_id'];
            $item_type = $row['item_type'];
            $item_name = $row['item_name'];
            $item_description = $row['item_description'];
            $unit = $row['unit'];
            $unit_cost = $row['unit_cost'];
            $project_name = $row['project_name'];
            $remarks = $row['remarks'];
            $balance = $quantity - $issued + $returned;
    ?>
            <!-- Modal Edit Item <?php echo $inventory_id ?> -->
            <div class="modal fade" id="EditItemModal<?php echo $inventory_id ?>" tabindex="-1" role="dialog" aria-labelledby="EditItemModal<?php echo $inventory_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header elegant-color text-white d-flex justify-content-center">
                            <h1 class="modal-title">Edit <?php echo $item_name ?> Item</h1>
                        </div>
                        <div class="modal-body">
                            <!-- Material form grid -->
                            <form action="#" method="post">
                                <!-- Grid row -->
                                <div class="row">
                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <select name="edit_item_type" class="browser-default custom-select">
                                                <?php
                                                $recent_type = $item_type;
                                                if ($recent_type == "Materials") { ?>
                                                    <option value="<?php echo $item_type ?>" selected>Recent: <?php echo $item_type ?></option>
                                                    <option value="Tools">Tools</option>
                                                    <option value="Safety">Safety</option>
                                                    <option value="Admin">Admin</option>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                if ($recent_type == "Tools") { ?>
                                                    <option value="<?php echo $item_type ?>" selected>Recent: <?php echo $item_type ?></option>
                                                    <option value="Materials">Materials</option>
                                                    <option value="Safety">Safety</option>
                                                    <option value="Admin">Admin</option>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                if ($recent_type == "Safety") { ?>
                                                    <option value="<?php echo $item_type ?>" selected>Recent: <?php echo $item_type ?></option>
                                                    <option value="Materials">Materials</option>
                                                    <option value="Tools">Tools</option>
                                                    <option value="Admin">Admin</option>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                if ($recent_type == "Admin") { ?>
                                                    <option value="<?php echo $item_type ?>" selected>Recent: <?php echo $item_type ?></option>
                                                    <option value="Materials">Materials</option>
                                                    <option value="Tools">Tools</option>
                                                    <option value="Safety">Safety</option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-6">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_project_name" class="form-control validate" id="edit_project_name" value="<?php echo $project_name ?>" required>
                                            <label for="edit_project_name" data-error="wrong" data-success="right">Project
                                                Name</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-6">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_item_name" class="form-control validate" id="edit_item_name" value="<?php echo $item_name ?>" required>
                                            <label for="edit_item_name" data-error="wrong" data-success="right">Item
                                                Name</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_item_description" class="form-control validate" id="edit_item_description" value="<?php echo $item_description ?>" required>
                                            <label for="edit_item_description" data-error="wrong" data-success="right">Item
                                                Description</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-6">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_unit" class="form-control validate" id="edit_unit" value="<?php echo $unit ?>" required>
                                            <label for="edit_unit" data-error="wrong" data-success="right">Unit</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-6">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_unit_cost" class="form-control validate" id="edit_unit_cost" value="<?php echo $unit_cost ?>" step="0.01" min="0" required>
                                            <label for="edit_unit_cost" data-error="wrong" data-success="right">Unit
                                                Cost</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-12">
                                        <!-- Material input -->
                                        <div class="md-form mt-0">
                                            <input type="text" name="edit_remarks" class="form-control validate" id="edit_remarks" value="<?php echo $remarks ?>" required>
                                            <label for="edit_remarks" data-error="wrong" data-success="right">Remarks</label>
                                        </div>
                                    </div>
                                    <!-- Grid column -->
                                </div>
                                <!-- Grid row -->
                                <div class="text-center">
                                    <input type="hidden" name="inventory_id" value="<?php echo $inventory_id ?>">
                                    <button type="submit" name="edit_item" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                            <!-- Material form grid -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.Modal Edit Item <?php echo $inventory_id ?> -->

            <!-- Modal Confirm Delete <?php echo $inventory_id ?> -->
            <div class="modal fade" id="ModalConfirmDelete<?php echo $inventory_id ?>" tabindex="-1" role="dialog" aria-labelledby="ModalConfirmDelete<?php echo $inventory_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-md modal-notify modal-danger" role="document">
                    <!--Content-->
                    <div class="modal-content text-center">
                        <!--Header-->
                        <div class="modal-header d-flex justify-content-center">
                            <p class="heading">Are you sure?</p>
                        </div>

                        <!--Body-->
                        <div class="modal-body">
                            <h4>Do you want to delete <?php echo $item_name ?>?</h4>
                            <i class="fas fa-times fa-4x animated rotateIn"></i>

                        </div>

                        <!--Footer-->
                        <div class="modal-footer flex-center">
                            <form action="#" method="post">
                                <input type="hidden" name="item_name" value="<?php echo $item_name ?>">
                                <input type="hidden" name="inventory_id" value="<?php echo $id ?>">
                                <button type="submit" class="btn btn-danger" name="delete_item">Yes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
            <!-- Modal Confirm Delete <?php echo $inventory_id ?> -->

            <!-- Modal Item Details <?php echo $inventory_id ?> -->
            <div class="modal fade" id="DetailsModal<?php echo $inventory_id ?>" tabindex="-1" role="dialog" aria-labelledby="DetailsModal<?php echo $inventory_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header elegant-color text-white d-flex justify-content-center">
                            <h1 class="modal-title"><?php echo $item_name ?> Details</h1>
                        </div>
                        <div class="modal-body">
                            <!-- Material form grid -->
                            <!-- Grid row -->
                            <div class="row">
                                <!-- Grid column -->
                                <div class="col-12">
                                    <!-- Material input -->
                                    <div class="md-form mt-0">
                                        <input type="text" name="item_description" class="form-control disabled" id="item_description" value="<?php echo $item_description ?>">
                                        <label for="item_description">Item Description</label>
                                    </div>
                                </div>
                                <!-- Grid column -->

                                <!-- Grid column -->
                                <div class="col-12">
                                    <!-- Material input -->
                                    <div class="md-form mt-0">
                                        <input type="text" name="unit_cost" class="form-control disabled" id="unit_cost" value="<?php echo $unit_cost ?>">
                                        <label for="unit_cost">Unit Cost</label>
                                    </div>
                                </div>
                                <!-- Grid column -->

                                <!-- Grid column -->
                                <div class="col-12">
                                    <!-- Material input -->
                                    <div class="md-form mt-0">
                                        <input type="text" name="remarks" class="form-control disabled" id="remarks" value="<?php echo $remarks ?>">
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <!-- Grid column -->
                            </div>
                            <!-- Grid row -->
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            <!-- Material form grid -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.Modal Item Details <?php echo $inventory_id ?> -->

            <!-- Modal Movement <?php echo $inventory_id ?> -->
            <div class="modal fade" id="MovementModal<?php echo $inventory_id ?>" tabindex="-1" role="dialog" aria-labelledby="MovementModal<?php echo $inventory_id ?>" aria-hidden="true">
                <div class="modal-dialog modal-fluid" role="document">
                    <div class="modal-content">
                        <div class="modal-header elegant-color text-white d-flex justify-content-center">
                            <h1 class="modal-title text-center"><?php echo $item_type ?> Item <?php echo $item_name ?>'s Movement for Project <?php echo $project_name ?></h1>
                        </div>
                        <div class="modal-body">
                            <!-- Material form grid -->
                            <!-- Grid row -->
                            <div class="row">
                                <!-- Grid column -->
                                <div class="col-12">
                                    <table id="dtMovement" class="table table-striped table-responsive-lg btn-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="th-sm">ID
                                                </th>
                                                <th class="th-sm">Quantity
                                                </th>
                                                <th class="th-sm">Date Added
                                                </th>
                                                <th class="th-sm">Issued
                                                </th>
                                                <th class="th-sm">Date Issued
                                                </th>
                                                <th class="th-sm">Returned
                                                </th>
                                                <th class="th-sm">Date Returned
                                                </th>
                                                <th class="th-sm">Balance
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SELECT all from inventory table
                                            $query_movement = "SELECT inventory_tb.*, movement_tb.*
                                            FROM inventory_tb INNER JOIN movement_tb 
                                            ON movement_tb.inventory_id = inventory_tb.inventory_id
                                            WHERE inventory_tb.inventory_id = $inventory_id;";

                                            $result_movement = mysqli_query($conn, $query_movement);

                                            if (mysqli_num_rows($result_movement) > 0) {
                                                // Display data of each row
                                                while ($row = mysqli_fetch_assoc($result_movement)) {
                                                    $movement_id = $row['movement_id'];
                                                    $quantity = $row['quantity'];
                                                    $date_added = $row['date_added'];
                                                    $issued = $row['issued'];
                                                    $date_issued = $row['date_issued'];
                                                    $returned = $row['returned'];
                                                    $date_returned = $row['date_returned'];
                                                    $balance = $row['balance'];
                                            ?>
                                                    <tr class="text-center">
                                                        <td>Movement <?php echo $movement_id ?></td>
                                                        <td><?php echo $quantity ?></td>
                                                        <td><?php echo $date_added ?></td>
                                                        <td><?php echo $issued ?></td>
                                                        <td><?php echo $date_issued ?></td>
                                                        <td><?php echo $returned ?></td>
                                                        <td><?php echo $date_returned ?></td>
                                                        <td><?php echo $balance ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            } else { ?>
                                                <tr>
                                                    <td>No Movement Data</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <th class="th-sm">ID
                                                </th>
                                                <th class="th-sm">Quantity
                                                </th>
                                                <th class="th-sm">Date Added
                                                </th>
                                                <th class="th-sm">Issued
                                                </th>
                                                <th class="th-sm">Date Issued
                                                </th>
                                                <th class="th-sm">Returned
                                                </th>
                                                <th class="th-sm">Date Returned
                                                </th>
                                                <th class="th-sm">Balance
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- Grid column -->
                            </div>
                            <!-- Grid row -->
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            <!-- Material form grid -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.Modal Movement <?php echo $inventory_id ?> -->
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
    <script type="text/javascript" src="dist/js/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="dist/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="dist/js/mdb.min.js"></script>
    <!-- MDBootstrap Datatables  -->
    <script type="text/javascript" src="dist/js/addons/datatables.min.js"></script>
    <!-- Your custom scripts (optional) -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dtBasicExample').DataTable({});
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dtMovement').DataTable({});
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

</body>

</html>