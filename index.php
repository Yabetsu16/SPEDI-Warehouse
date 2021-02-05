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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#spediNavBar"
      aria-controls="spediNavBar" aria-expanded="false" aria-label="Toggle navigation">
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
          <a class="nav-link" href="project_management.php">Project Management</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="item_management.php">Item Management</a>
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

  <!-- Warehouse Counter -->
  <section id="warehouse_counter" class="mt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 col-lg-6 col-xl-3 mt-3">
          <div class="card">
            <div class="card-header text-center elegant-color text-white">
              <h4 class="card-title">Total Material Items</h4>
            </div>
            <div class="card-body text-center">
              <p class="h1">0</p>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-3 mt-3">
          <div class="card">
            <div class="card-header text-center special-color text-white">
              <h4 class="card-title">Total Tool Items</h4>
            </div>
            <div class="card-body text-center">
              <p class="h1">0</p>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-3 mt-3">
          <div class="card">
            <div class="card-header text-center unique-color text-white">
              <h4 class="card-title">Total Safety Items</h4>
            </div>
            <div class="card-body text-center">
              <p class="h1">0</p>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-3 mt-3">
          <div class="card">
            <div class="card-header text-center stylish-color text-white">
              <h4 class="card-title">Total Admin Items</h4>
            </div>
            <div class="card-body text-center">
              <p class="h1">0</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xl-4"></div>
        <div class="col-sm-12 col-lg-6 col-xl-4 mt-3">
          <div class="card">
            <div class="card-header text-center stylish-color text-white">
              <h4 class="card-title">Overall Total Items</h4>
            </div>
            <div class="card-body text-center">
              <p class="h1">0</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xl-4"></div>
      </div>
    </div>
  </section>
  <!-- /. Warehouse Counter -->

  <!-- Recent Movements -->
  <section id="recent_movements" class="mt-3 mb-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header elegant-color text-white">
              <h4 class="card-title text-center">Recent Movements</h4>
            </div>
            <div class="card-body">
              <div class="float-right">
                <br>
                <form action="" method="post">
                  <button class="btn btn-sm btn-danger" type="submit" name="clear_recent">Clear</button>
                </form>
              </div>
              <br>

              <!-- <div class="container mt-3">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  No Recent Movements.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>                
              </div> -->
              <table id="dtBasicExample" class="table table-striped table-responsive-md table-bordered table-sm text-center"
                cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="th-sm">Name
                    </th>
                    <th class="th-sm">Description
                    </th>
                    <th class="th-sm">Balance
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Cement</td>
                    <td>Sack of Cement</td>
                    <td class="red lighten-2">5</td>
                  </tr>
                  <tr>
                    <td>Cement</td>
                    <td>Sack of Cement</td>
                    <td class="light-green lighten-2">5</td>
                  </tr>
                  <tr>
                    <td>Cement</td>
                    <td>Sack of Cement</td>
                    <td>5</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Name
                    </th>
                    <th>Description
                    </th>
                    <th>Balance
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
  <!-- /.Recent Movements-->
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
    $(document).ready(function () {
      $('#dtBasicExample').DataTable({
        "searching": false
      });
      $('.dataTables_length').addClass('bs-select');
    });
  </script>

</body>

</html>