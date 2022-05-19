<?php
include_once "../components/DB_CONNECTION.php";

$errors =[];
$success = false;

if ($_SERVER['REQUEST_METHOD']=="POST") {
    $name = $_POST['a-name'];
    $phone = $_POST['a-phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    isset($_POST['status-n']) ? $status = 1 : $status = 0;

    if (empty($name)) {
        $errors['name-error'] = "Name is Required. Please fill it";
    }

    if (empty($phone)) {
        $errors['phone-error'] = "Phone Number is Required. Please fill it";
    }

    if (empty($email)) {
        $errors['email-error'] = "Email is Required. Please fill it";
    }

    $check_query = "select password from admin where id = ".$_GET['id'];
    $check = mysqli_query($connection, $check_query);
    $get_result = mysqli_fetch_assoc($check);

    if ($get_result['password'] == $_POST['pass']) {
        $password = $_POST['pass'];
    } else {
        $password = md5($_POST['pass']);
    }

    if (empty($password)) {
        $errors['password'] = "Password is Required. Please fill it";
    }

    if (empty($address)) {
        $errors['add'] = "Address is Required. Please fill it";
    }
    if (empty($description)) {
        $errors['des'] = "Description is Required. Please fill it";
    }

    if (count($errors) > 0) {
        $errors['general_error'] = "please fix all errors";
    } else {
        $query = "update admin set name = '$name', phone = '$phone', email = '$email', password = '$password',
                 status = '$status', address = '$address', description = '$description' where id =" . $_GET['id'];
        $result = mysqli_query($connection, $query);

        if ($result) {
            $errors=[];
            $success = true;
            header('Location:show_admins.php');
        } else {
            $errors['general-errors']=mysqli_error($connection);
            echo "Error".mysqli_error($connection);
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "select * from admin where id = $id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<?php
include "../components/header.php";
?>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click"
      data-menu="vertical-menu-modern" data-col="2-columns">

<!-- fixed-top-->
<?php include "../components/nav.php" ?>
<?php include "../components/sidebar.php" ?>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">

            <!-- Basic form layout section start -->
            <section id="basic-form-layouts">
                <div class="row match-height">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Admin Info</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <?php
                                    if (!empty($errors['general_error'])) {
                                        echo "<div class='alert alert-danger'>" . $errors["general_error"] . "</div>";
                                    } elseif ($success) {
                                        echo "<div class='alert alert-success'>Admin Added Successfully</div>";

                                    }
                                    ?>
                                    <form class="form" method="post" action=" <?php echo $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']  ?>">
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i>Add New Admin</h4>
                                            <div class="row">
                                                <!-- Name -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Name</label>
                                                        <input type="text" id="projectinput1" class="form-control"
                                                               placeholder="Admin name" name="a-name" value="<?php echo $row['name']?>"/>
                                                        <?php
                                                        if (!empty($errors['name_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['name_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Phone -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput2">Phone</label>
                                                        <input type="text" id="projectinput2" class="form-control"
                                                               placeholder="Phone Number" name="a-phone" value="<?php echo $row['phone']?>"/>
                                                        <?php
                                                        if (!empty($errors['phone_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['phone_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3">Email</label>
                                                        <input type="email" id="projectinput3" class="form-control"
                                                               placeholder="Email" name="email" value="<?php echo $row['email']?>"/>
                                                        <?php
                                                        if (!empty($errors['email_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['email_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Password -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput4">Password</label>
                                                        <input type="password" id="projectinput4" class="form-control"
                                                               placeholder="Password" name="pass" value="<?php echo $row['password']?>"/>
                                                        <?php
                                                        if (!empty($errors['password_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['password_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3">Address</label>
                                                        <input type="text" id="projectinput3" class="form-control"
                                                               placeholder="Address" name="address" value="<?php echo $row['address']?>"/>
                                                        <?php
                                                        if (!empty($errors['address_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['address_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Description -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput4">Description</label>
                                                        <input type="text" id="projectinput4" class="form-control"
                                                               placeholder="Description" name="description" value="<?php echo $row['description']?>"/>
                                                        <?php
                                                        if (!empty($errors['description_error'])) {
                                                            echo "<span class='text-danger'>" . $errors['description_error'] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Status -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3">Status</label>
                                                        <input type="checkbox" placeholder="status" name="status-n"
                                                        <?php
                                                        if ($row['status'] == 1) {
                                                            echo "checked";
                                                        }
                                                        ?>/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <a href="show_admins.php">
                                                    <button type="button" class="btn btn-warning mr-1">
                                                        <i class="ft-x"></i> Cancel
                                                    </button>
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> Save
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>

<?php
include "../components/footer.php";
?>

</body>
</html>