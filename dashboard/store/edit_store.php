<?php
include "../components/DB_CONNECTION.php";

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD']=="POST") {
    $name = $_POST['m-name'];
    $description = $_POST['m-description'];
    $address = $_POST['address'];
    $phone = $_POST['m-phone'];
    $category = $_POST['category'];

    if (empty($name)) {
        $errors['name_error'] = "Name is required, please fill it";
    }

    if (empty($description)) {
        $errors['description_error'] = "Description is Required. Please fill it";
    }

    if (empty($address)) {
        $errors['add'] = "Address is Required. Please fill it";
    }
    if (empty($phone)) {
        $errors['phone'] = "Phone is Required. Please fill it";
    }

    if (count($errors) > 0) {
        $errors['general_error'] = "please fix all errors";
    } else {
        $query = "Update store Set name = '$name', description = '$description', address = '$address', phone_number = $phone , category_id = $category where id = ". $_GET['id'];
        $result = mysqli_query($connection, $query);
        $last_query_id = mysqli_insert_id($connection); // bring last index of last query had been processed

        if ($result) {
            $success = true;
            if (isset($_POST['image_update'])) {
                $query8 = "SELECT * from store where id = ".$_GET['id'];
                $result8 = mysqli_query($connection, $query8);
                while ($row8 = mysqli_fetch_assoc($result8)) {
                    unlink('../uploads/images/' . $row8['logo_image']);
                }
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_type = $_FILES['image']['type'];
                $file_tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_new_name = time() . rand(1, 100000) . "." . $file_ext;

                $upload_path = '../uploads/images/' . $file_new_name;
                move_uploaded_file($file_tmp_name, $upload_path);
                $query4 = "Update store Set logo_image = '$file_new_name' where id =".$_GET['id'];
                $result4 = mysqli_query($connection, $query4);
                if ($result4) {
                    $success = true;
                    header('Location:show_stores.php');
                }
            }
            header('Location:show_stores.php');
        } else {
            $errors['general_errors']=mysqli_error($connection);
            echo "Error" . mysqli_error($connection);
        }
    }
    header('Location:show_stores.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "select * from store where id = $id";
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
<?php include "../components/nav.php"?>
<?php include "../components/sidebar.php"?>

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
                                <h4 class="card-title" id="basic-layout-form">Store Info</h4>
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
                                        echo "<div class='alert alert-success'>Store Added Successfully</div>";
                                    }
                                    ?>

                                    <form class="form" method="post" action=" <?php echo $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] ?>" enctype="multipart/form-data">
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i>Add Store</h4>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Store Name</label>
                                                        <input type="text" id="projectinput1" class="form-control"
                                                               placeholder="Enter name of store" name="m-name" value="<?php echo $row['name']?>"/>
                                                        <?php
                                                        if (!empty($errors['name_error'])) {
                                                            echo "<span class='text-danger'>" . $errors["name_error"] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput2">Description</label>
                                                        <input type="text" id="projectinput2" class="form-control"
                                                               placeholder="Enter details of product" name="m-description" value="<?php echo $row['description']?>"/>
                                                        <?php
                                                        if (!empty($errors['description_error'])) {
                                                            echo "<span class='text-danger'>" . $errors["description_error"] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3">Address</label>
                                                        <input type="text" id="projectinput3" class="form-control"
                                                               placeholder="Address" name="address" value="<?php echo $row['address']?>"/>
                                                        <?php
                                                        if (!empty($errors['add'])) {
                                                            echo "<span class='text-danger'>" . $errors["add"] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput4">Phone Number</label>
                                                        <input type="text" id="projectinput4" class="form-control"
                                                               placeholder="Phone Number" name="m-phone" value="<?php echo $row['phone_number']?>"/>
                                                        <?php
                                                        if (!empty($errors['phone'])) {
                                                            echo "<span class='text-danger'>" . $errors["phone"] . "</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput5">Category</label>
                                                        <select class="form-control" name="category">
                                                            <?php
                                                            include "../components/DB_CONNECTION.php";
                                                            $query ="SELECT * from category";
                                                            $result= mysqli_query($connection,$query);
                                                            $cid = $row['category_id'];

                                                            while($row=mysqli_fetch_assoc($result)){
                                                                if ($cid == $row['category_id']){
                                                                    echo '<option value='. $row['id'].' selected>'.$row['name'].'</option>';
                                                                }
                                                                echo '<option value='. $row['id'].'>'.$row['name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox mt-2">
                                                            <input type="checkbox" class="custom-control-input " name='image_update' id="customCheck1">
                                                            <label class="custom-control-label" for="customCheck1">Update Market Logo</label>
                                                        </div>
                                                        <label id="projectinput8" class="file center-block form-control">
                                                            <input type="file"
                                                                   class="form-control" name="image">
                                                            <span class="file-custom"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-actions">
                                            <a href="show_stores.php">
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