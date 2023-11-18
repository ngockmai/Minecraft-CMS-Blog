<?php
include ('includes/config.php');
error_reporting(0);
$err_messages = [];
$is_valid = true;
if(strlen($_SESSION['login']) == 0){
    header("Location: index.php");
}
else{
    //Add title and page name
    $pagename="Manage Users";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;

    //Header: Add css to header.php
    $csslink = '<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';

    //Footer: Add script -  page level plugins - to footer.php
    $customscript = '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/demo/datatables-demo.js"></script>';

    //  Fetch data into the table 1 - Admin table
    $query1 = "SELECT * FROM users WHERE role_id = 1 AND active = 1;";
    $statement1 = $db->prepare($query1);
    $statement1->execute();

    //  Fetch data into the table 2 - User Role
    $query2 = "SELECT * FROM users WHERE role_id = 0 AND active = 1";
    $statement2 = $db->prepare($query2);
    $statement2->execute();

    //  Fetch data into the table 3 - Deleted User
    $query3 = "SELECT * FROM users WHERE active = 0";
    $statement3 = $db->prepare($query3);
    $statement3->execute();
    
    // Set Admin role to User role
    if($_GET['action'] == 'downrole' && $_GET['did']){
        $_GET['did'] = filter_input(INPUT_GET, 'did', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE users SET role_id = 0 WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $_GET['did']);
        if($statement->execute()){
            $msg="changed the role";
            header("Location: manage-users.php");
            exit;
        }
    }

    // Set User role to Admin role
    if($_GET['action'] == 'uprole' && $_GET['uid']){
        $_GET['uid'] = filter_input(INPUT_GET, 'uid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE users SET role_id = 1 WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $_GET['uid']);
        if($statement->execute()){
            $msg="changed the role";
            header("Location: manage-users.php");
            exit;
        }
    }

    //Delete users
    if($_GET['action'] == 'del' && $_GET['rid']){
        $_GET['rid'] = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE users SET active = 0 WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $_GET['rid']);
        if($statement->execute()){
            $msg="post deleted permenently";
            header("Location: manage-users.php");
            exit;
        }
    }

    //Restore users
    if($_GET['resid']){
        $_GET['resid'] = filter_input(INPUT_GET, 'resid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE users SET active = 1 WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $_GET['resid']);
        if($statement->execute()){
            $msg="post restored";
            header("Location: manage-users.php");
            exit;
        }
    }
    require_once('includes/header.php');
?>
            <!-- Content Row -->
            <div class="row">

            <!-- Message of actions (Todo)-->
                <!-- Table -->
                <div class="col-md-12">
					<div class="m-t-20">
                        <div class="mb-3">
                            <a href="add-user.php">
                                <button name="button" class="btn btn-success">Add</button>
                            </a>
                        </div>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h3 class="h4 ml-2 text-gray-800"><i class="fas text-secondary"></i> Admin Role</h1>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Registered At</th>
                                                <th>Role?</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <?php
                                            $count=1;
                                            while($row = $statement1->fetch()):
                                        ?>

                                        <tr>
                                        <th scope="row"><?= $count?></th>
                                        <td><?php echo $row['first_name']; echo ' ';echo $row['last_name'];?></td>
                                        <td><?php echo $row['email'];?></td>
                                        <td><?php echo $row['registered_at'];?></td>
                                        <td><a href="manage-users.php?did=<?php echo $row['id'];?>&&action=downrole"><i class="fas fa-arrow-down text-danger"></i></i> Set to User</a> </td>
                                        <td><a href="manage-users.php?rid=<?php echo $row['id'];?>&&action=del"><i class="fas fa-trash-alt text-danger"></i></a> </td>
                                        </tr>
                                        <?php
                                            $count++;
                                        endwhile
                                        ?>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h3 class="h4 ml-2 text-gray-800"><i class="fas text-secondary"></i> Users Role</h1>
                </div>
                <!-- User Role -->
                <!-- Table -->
                <div class="col-md-12">
                    <div class="m-t-20">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Registered At</th>
                                                <th>Role?</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count=1;
                                                while($row = $statement2->fetch()):
                                            ?>

                                            <tr>
                                            <th scope="row"><?= $count?></th>
                                            <td><?php echo $row['first_name']; echo ' ';echo $row['last_name'];?></td>
                                            <td><?php echo $row['email'];?></td>
                                            <td><?php echo $row['registered_at'];?></td>
                                            <td><a href="manage-users.php?uid=<?php echo $row['id'];?>&&action=uprole"><i class="fas fa-arrow-up text-primary"></i></i> Set to Admin</a> </td>
                                            <td><a href="manage-users.php?rid=<?php echo $row['id'];?>&&action=del"><i class="fas fa-trash-alt text-danger"></i></a></td>
                                            </tr>
                                            <?php
                                                $count++;
                                            endwhile
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deleted Users -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h3 class="h4 ml-2 text-gray-800"><i class="fas text-secondary"></i> Inactive Users</h1>
                </div>
                
                <!-- Table -->
                <div class="col-md-12">
                    <div class="m-t-20">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Registered At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count=1;
                                                while($row = $statement3->fetch()):
                                            ?>

                                            <tr>
                                            <th scope="row"><?= $count?></th>
                                            <td><?php echo $row['first_name']; echo ' ';echo $row['last_name'];?></td>
                                            <td><?php echo $row['email'];?></td>
                                            <td><?php echo $row['registered_at'];?></td>
                                            <td><a href="manage-users.php?resid=<?php echo $row['id'];?>"><i class="fas fa-undo"></i></i></a> 

                                            </tr>
                                            <?php
                                                $count++;
                                            endwhile
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include ('includes/footer.php') ?>      
<?php } ?>