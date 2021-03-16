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
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Dashboard
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="project_office_management.php">Project / Office Management</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="item_management.php">Item Management</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="summary.php">Summary</a>
        </li>
      </ul>
      <!-- Links -->

      <ul class="navbar-nav ml-auto nav-flex-icons">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-capitalize" id="navbarDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $username ?> <i class="fas fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropDown">
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
    <!-- Collapsible content -->
  </nav>
  <!--/.Navbar-->

  < <!-- Warehouse Counter -->
    <section id="warehouse_counter" class="mt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 col-lg-6 col-xl-4 mt-3">
            <div class="card">
              <div class="card-header text-center elegant-color text-white">
                <h4 class="card-title">Total Material Items</h4>
              </div>
              <div class="card-body text-center">
                <?php
                $query = "SELECT COUNT(item_type) FROM inventory_tb WHERE item_type = 'Materials'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $count = $row['COUNT(item_type)'];
                ?>
                  <p class="h1-responsive"><?php echo $count ?></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-4 mt-3">
            <div class="card">
              <div class="card-header text-center special-color text-white">
                <h4 class="card-title">Total Tool Items</h4>
              </div>
              <div class="card-body text-center">
                <?php
                $query = "SELECT COUNT(item_type) FROM inventory_tb WHERE item_type = 'Tools'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $count = $row['COUNT(item_type)'];
                ?>
                  <p class="h1-responsive"><?php echo $count ?></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-4 mt-3">
            <div class="card">
              <div class="card-header text-center unique-color text-white">
                <h4 class="card-title">Total Safety Items</h4>
              </div>
              <div class="card-body text-center">
                <?php
                $query = "SELECT COUNT(item_type) FROM inventory_tb WHERE item_type = 'Safety'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $count = $row['COUNT(item_type)'];
                ?>
                  <p class="h1-responsive"><?php echo $count ?></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-6 mt-3">
            <div class="card">
              <div class="card-header text-center primary-color-dark text-white">
                <h4 class="card-title">Projects / Offices</h4>
              </div>
              <div class="card-body text-center">
                <?php
                $query = "SELECT COUNT(project_office_name) FROM project_office_tb";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $count = $row['COUNT(project_office_name)'];
                ?>
                  <p class="h1-responsive"><?php echo $count ?></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-6 mt-3">
            <div class="card">
              <div class="card-header text-center info-color-dark text-white">
                <h4 class="card-title">Overall Total Items</h4>
              </div>
              <div class="card-body text-center">
                <?php
                $query = "SELECT COUNT(item_name) FROM inventory_tb WHERE item_type = 'Materials' OR item_type = 'Tools' OR item_type = 'Safety'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $count = $row['COUNT(item_name)'];
                ?>
                  <p class="h1-responsive"><?php echo $count ?></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /. Warehouse Counter -->

    <!-- Recent Items -->
    <?php
    if (isset($_POST['clear_recent'])) {
      $query = "DELETE FROM recent_tb WHERE type = 3";
      $stmt = $conn->prepare($query);
      $stmt->execute();
    }
    ?>
    <section id="recent_movements" class="mt-3 mb-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header elegant-color text-white">
                <h4 class="card-title text-center">Recently Added Items</h4>
              </div>
              <div class="card-body">
                <div class="float-right">
                  <br>
                  <form action="#" method="post">
                    <button class="btn btn-sm btn-danger" type="submit" name="clear_recent">Clear</button>
                  </form>
                </div>
                <br>
                <table id="dtBasicExample" class="table table-striped table-responsive-md table-bordered table-sm text-center" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th class="th-sm">Item Type
                      </th>
                      <th class="th-sm">Item Name
                      </th>
                      <th class="th-sm">Description
                      </th>
                      <th class="th-sm">Project / Office
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT * FROM recent_tb INNER JOIN inventory_tb ON inventory_tb.inventory_id = recent_tb.inventory_id
                  INNER JOIN project_office_tb ON project_office_tb.project_id = inventory_tb.project_id
                  WHERE recent_tb.type = 3";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                      $item_type = $row['item_type'];
                      $item_name = $row['item_name'];
                      $item_description = $row['item_description'];
                      $project_office_name = $row['project_office_name'];
                    ?>
                      <tr>
                        <td><?php echo $item_type ?></td>
                        <td><?php echo $item_name ?></td>
                        <td><?php echo $item_description ?></td>
                        <td><?php echo $project_office_name ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="th-sm">Item Type
                      </th>
                      <th class="th-sm">Item Name
                      </th>
                      <th class="th-sm">Description
                      </th>
                      <th class="th-sm">Project / Office
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
    <!-- /.Recent Items-->
    <br>
    <br>

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
          "searching": false
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