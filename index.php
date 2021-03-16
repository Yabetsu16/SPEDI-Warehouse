<?php require "config/connectdb.php";
session_start();

if (isset($_SESSION['user_type']) == 1) {
    header('Location: Superadmin/ ');
} else if (isset($_SESSION['user_type']) == 2) {
    header('Location: Admin/ ');
} else if (isset($_SESSION['user_type']) == 3) {
    header('Location: Material_Control/ ');
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
    <link rel="stylesheet" href="dist/css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="dist/css/mdb.min.css">
    <!-- MDBootstrap Datatables  -->
    <link href="dist/css/addons/datatables.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="dist/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <?php
                if (isset($_POST['login'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    if ($username == "" && $password == "") { ?>
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            Username and password required.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                    } else if ($username <> "" && $password == "") { ?>
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            Password required.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                    } else if ($username == "" && $password <> "") { ?>
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            Username required.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    } else {
                        $query_login = "SELECT * FROM account_tb WHERE username = ? AND password = ?";
                        $stmt = $conn->prepare($query_login);
                        $stmt->bind_param("ss", $username, $password);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $account_id = $row['account_id'];
                                $username = $row['username'];
                                $user_type = $row['user_type'];
                                session_start();
                                $_SESSION['id'] = $account_id;
                                $_SESSION['username'] = $username;
                                $_SESSION['user_type'] = $user_type;

                                if ($user_type == 1) {
                                    header("Location: Superadmin/");
                                }
                                else if ($user_type == 2) {
                                    header("Location: Admin/");
                                }
                                else if ($user_type == 3) {
                                    header("Location: Material_Control/");
                                }
                            }
                        } else { ?>
                            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                                Wrong username or password
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                <?php
                        }
                    }
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="border border-light p-5">

                    <p class="h4 mb-5 text-center">Welcome to SPEDI Warehouse</p>

                    <div class="md-form mb-5">
                        <input type="text" id="username" name="username" class="form-control">
                        <label for="username">Username</label>
                    </div>

                    <div class="input-group md-form mb-5" id="show_hide_password">
                        <input type="password" id="password" name="password" class="form-control">
                        <label for="password">Password</label>
                        <div class="input-group-append">
                            <button class="btn btn-md btn-outline-primary m-0 px-3 py-2 z-depth-0 waves-effect" type="button" id="button-addon2"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                        </div>
                    </div>

                    <button class="btn btn-info btn-block my-4" type="submit" name="login">Login</button>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

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
            $('#dtBasicExample').DataTable({
                "searching": false
            });
            $('.dataTables_length').addClass('bs-select');
        });

        $(document).ready(function() {
            $("#show_hide_password button").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("fa-eye-slash");
                    $('#show_hide_password i').removeClass("fa-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("fa-eye-slash");
                    $('#show_hide_password i').addClass("fa-eye");
                }
            });
        });
    </script>

</body>

</html>