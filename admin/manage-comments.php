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
    $pagename="Manage Comments";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;

    //Header: Add css to header.php
    $csslink = '<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';

    //Footer: Add script -  page level plugins - to footer.php
    $customscript = '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/demo/datatables-demo.js"></script>';

    //Fetch data into the table Manage comments
    $query1 = "SELECT pc.id, pc.title, pc.content, pc.created_at, u.email 
                FROM users as u
                    INNER JOIN posts as p 
                        ON u.id = p.author_id
                    INNER JOIN `post-comments` as pc
                        ON p.id = pc.post_id                    
                WHERE pc.active = 1";
    $statement1 = $db->prepare($query1);
    $statement1->execute();

    //Fetch data into the table 2 - unapproved comments
    $query2 = "SELECT pc.id, pc.title, pc.content, pc.created_at, u.email 
    FROM users as u
        INNER JOIN posts as p 
            ON u.id = p.author_id
        INNER JOIN `post-comments` as pc
            ON p.id = pc.post_id                    
    WHERE pc.active = 0";
    $statement2 = $db->prepare($query2);
    $statement2->execute();

    //Fetch Category_id to Category name (title)
    
    //Delete post
    if($_GET['action'] == 'del' && $_GET['rid']){
        $_GET['rid'] = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE `post-comments` SET active = 0 WHERE id = :id";
        $statement1 = $db->prepare($query);
        $statement1->bindValue(':id', $_GET['rid']);
        if($statement1->execute()){
            $msg="post deleted";
            header("Location: manage-comments.php");
            exit;
        }
    }

    //Delete category parmenently
    if($_GET['action'] == 'parmdel' && $_GET['rid']){
        $_GET['rid'] = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM posts WHERE id = :id";
        $statement1 = $db->prepare($query);
        $statement1->bindValue(':id', $_GET['rid']);
        if($statemen1->execute()){
            $msg="post deleted permenently";
            header("Location: manage-posts.php");
            exit;
        }
    }

    //  Restore comments
    if($_GET['resid']){
        $_GET['resid'] = filter_input(INPUT_GET, 'resid', FILTER_SANITIZE_NUMBER_INT);
        $query = "UPDATE `post-comments` SET active = 1 WHERE id = :id";
        $statement1 = $db->prepare($query);
        $statement1->bindValue(':id', $_GET['resid']);
        if($statement1->execute()){
            $msg="comment restored";
            header("Location: manage-comments.php");
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
                            <a href="add-post.php">
                                <button name="button" class="btn btn-success">Add</button>
                            </a>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title Name</th>
                                                <th>Content</th>
                                                <th>Created At</th>
                                                <th>Author</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Title Name</th>
                                                <th>Content</th>
                                                <th>Created At</th>
                                                <th>Author</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    <tbody>
                                        <?php
                                            $count=1;
                                            while($row = $statement1->fetch()):
                                        ?>

                                        <tr>
                                        <th scope="row"><?= $count?></th>
                                        <td><?php echo $row['title'];?></td>
                                        <td><?php echo $row['content'];?></td>
                                        <td><?php echo $row['created_at'];?></td>
                                        <td><?php echo $row['email'];?></td>
                                        <td><a href="manage-comments.php?rid=<?php echo $row['id'];?>&&action=del"><i class="fas fa-trash-alt text-danger"></i></a> </td>
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
                    <h3 class="h4 ml-2 text-gray-800"><i class="fas fa-trash-alt text-secondary"></i> Unapproved Comments</h1>
                </div>
                <!-- Deleted categories -->
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
                                                    <th>Title Name</th>
                                                    <th>Content</th>
                                                    <th>Created At</th>
                                                    <th>Author</th>
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
                                            <td><?php echo $row['created_at'];?></td>
                                            <td><?php echo $row['email'];?></td>
                                            <td><a href="manage-comments.php?resid=<?php echo $row['id'];?>"><i class="fas fa-undo"></i></i></a> 
                                                &nbsp;<a href="manage-comments.php?rid=<?php echo $row['id'];?>&&action=parmdel"><i class="fas fa-trash-alt text-danger"></i></a></td>
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