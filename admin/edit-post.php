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
    $pagename="Edit Post";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;

    //  Custom CSS
    $csslink = '<script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>';

    //Check Get and sanitize data before query
    if(isset($_GET['cid'])){
        $_GET['cid'] = filter_input(INPUT_GET, 'cid', FILTER_SANITIZE_NUMBER_INT);
    }

    //Update Image
    // if(!isset($_POST['check'])){
    //     $query_image = "UPDATE posts
    //                         SET posts_image = NULL
    //                         WHERE id = :id";
    //     $statement = $db->prepare($query);
    //     $statement->bindValue(':id', $_GET['cid']);
    //     if($statement->execute()){
    //         echo "<script>alert('Success')</script>";
    //         exit;
    //     }
    // }
    //Update post
    if(isset($_POST['post_title'], $_POST['submit'])){
        if(strlen($_POST['post_title']) >= 1 && strlen($_POST['post_title']) <= 80){
            $_POST['post_title'] = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $_POST['category'] = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $_POST['post_editor'] = filter_input(INPUT_POST, 'post_editor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "UPDATE posts 
                        SET post_title = :post_title, meta_title = :meta_title, category_id = :category_id, content = :content 
                        WHERE id = :id";
            // $query = "INSERT INTO category (parent_id, title, content) VALUES (:parent_id, :title, :content)";
            $statement = $db->prepare($query);
 
            //  Bind values to the parameters
            $statement->bindValue(':id', $_GET['cid']);
            $statement->bindValue(':post_title', $_POST['post_title']);
            $statement->bindValue(':meta_title', $_POST['meta_title']);
            $statement->bindValue(':category_id', $_POST['category']);
            $statement->bindValue(':content', $_POST['post_editor']);

            //  Execute the INSERT.
            //  execute() will check for possible SQL injection and remove if necessary
            if($statement->execute()){
                echo "<script>alert('Success')</script>";
                header("Location: manage-posts.php");
                exit;
            }
        }
        if(empty($_POST['post_title'])){
            array_push($err_messages, "Title is empty! Please check again");   
            $is_valid = false;
        }
        else if(strlen($_POST['post_title']) > 80){
            array_push($err_messages, "Title is longer than 80 characters, please check it again!");
            $is_valid = false;
        }
        if(!$is_valid){
            foreach($err_messages as $err){
                echo $err,"<br/>";
            }
        } 
    }
    // Fetch data to fill into the form with cid 
    $query1 = "SELECT p.post_title, p.meta_title, p.posts_image, c.id, c.title, p.content
                FROM posts as p
                    INNER JOIN category as c 
                        ON p.category_id = c.id 
                WHERE p.id = :id";
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':id',$_GET['cid']);
    $statement1->execute();
    // print_r($statement1->fetch());

    //add header
    require_once('includes/header.php');
?>
            <!-- Content Row -->
            <div class="row">

                <!-- Add form -->
                <div class="col-md-12">
                    <form name="posts" method="post">
                    <?php while($row = $statement1->fetch()): ?>
	                <div class="form-group">
	                    <label class="col-md-6">Post Title</label>
	                        <div class="col-md-12">
	                            <input type="text" class="form-control" value="<?= $row['post_title']?>" name="post_title" required>
	                        </div>
	                </div>

                    <div class="form-group">
	                    <label class="col-md-12">Meta Title</label>
	                        <div class="col-md-12">
	                            <input type="text" class="form-control" value="<?= $row['meta_title']?>" name="meta_title">
	                        </div>
	                </div>
                    <div class="form-group">
                        <label class="col-md-6">Category</label>
                        <div class="col-md-6">
                        <select class="custom-select" name="category">
                            <option>Choose...</option>                          
                            <option selected value="<?= $row['id']?>"><?= $row['title']?></option>
                            
                        </select>
                        </div>
                    </div>
                                                        
	                <div class="form-group">
	                    <label class="col-md-6">Post Details</label>
	                    <textarea name="post_editor"><?= $row['content']?></textarea> 
                        <script>
                        CKEDITOR.replace( 'post_editor' );
                        </script>   
	                </div>
                    
                    <div class="form-group">
                    <input type="checkbox" name="check">
                        <label class="col-md-6">Feature Image</label>
                        
                        <input type="file" class="form-control" style="height:auto" id="post-image" name="postimage">
                        <span>Current Image File Path: <?= $row['posts_image'] ?></span>
                    </div>

                    <div class="form-group">
                            <div class="col-md-2">                                          
                                <button type="submit" class="btn btn-primary" name="submit">
                                    Submit
                                </button>
                            </div>
                    </div>
                    <?php endwhile?>
	                </form>
                </div>               
            </div>
    <?php include ('includes/footer.php') ?>   
<?php } ?>