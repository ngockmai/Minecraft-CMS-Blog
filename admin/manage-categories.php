<?php
include ('includes/config.php');
error_reporting(0);
$err_messages = [];
$is_valid = true;
if(strlen($_SESSION['login']) == 0){
    header("Location: index.php");
}
else{
    $pagename="Manage Categories";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;

    //Fetch data into the table 1
    $query1 = "SELECT id, title, content FROM category WHERE active = 1";
    $statement1 = $db->prepare($query1);
    $statement1->execute();

    //Fetch data into the table 2
    $query2 = "SELECT id, title, content FROM category WHERE active = 0";
    $statement2 = $db->prepare($query2);
    $statement2->execute();

    //Delete category
    if($_GET['action'] == 'del' && $_GET['rid']){
        $_GET['rid'] = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE category SET active = 0 WHERE id = :id";
        $statement1 = $db->prepare($query1);
        $statement1->bindValue(':id', $_GET['rid']);
        if($statement1->execute()){
            $msg="Category deleted";
            header("Location: manage-categories.php");
            exit;
        }
    }

    //Delete category parmenently
    if($_GET['action'] == 'parmdel' && $_GET['rid']){
        $_GET['rid'] = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM category WHERE id = :id";
        $statement1 = $db->prepare($query);
        $statement1->bindValue(':id', $_GET['rid']);
        if($statement1->execute()){
            $msg="Category deleted permenently";
            header("Location: manage-categories.php");
            exit;
        }
    }

    //Restore category
    if($_GET['resid']){
        $_GET['resid'] = filter_input(INPUT_GET, 'resid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE category SET active = 1 WHERE id = :id";
        $statement1 = $db->prepare($query);
        $statement1->bindValue(':id', $_GET['resid']);
        if($statement1->execute()){
            $msg="Category restored";
            header("Location: manage-categories.php");
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
					<div class="demo-box m-t-20">
                        <div class="mb-3">
                            <a href="add-category.php">
                                <button name="button" class="btn btn-success">Add</button>
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-colored-bordered table-bordered-primary">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                            <th>Category</th>
                                            <th>Description</th>
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
                                <td><?php echo $row['title'];?></td>
                                <td><?php echo $row['content'];?></td>
                                <td><a href="edit-category.php?cid=<?php echo $row['id'];?>"><i class="far fa-edit"></i></a> 
                                    &nbsp;<a href="manage-categories.php?rid=<?php echo $row['id'];?>&&action=del"><i class="fas fa-trash-alt text-danger"></i></a> </td>
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

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h3 class="h4 ml-2 text-gray-800"><i class="fas fa-trash-alt text-secondary"></i> Deleted Categories</h1>
                </div>
                <!-- Deleted categories -->
                <!-- Table -->
                <div class="col-md-12">
					<div class="demo-box m-t-20">

                        <div class="table-responsive">
                            <table class="table table-colored-bordered table-bordered-primary">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                            <th>Category</th>
                                            <th>Description</th>
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
                                <td><?php echo $row['title'];?></td>
                                <td><?php echo $row['content'];?></td>
                                <td><a href="manage-categories.php?resid=<?php echo $row['id'];?>"><i class="fas fa-undo"></i></i></a> 
                                    &nbsp;<a href="manage-categories.php?rid=<?php echo $row['id'];?>&&action=parmdel"><i class="fas fa-trash-alt text-danger"></i></a> </td>
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
<?php include ('includes/footer.php') ?>      
<?php } ?>