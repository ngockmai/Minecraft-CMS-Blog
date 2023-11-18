<?php
include ('includes/config.php');
error_reporting(0);
$err_messages = [];
$is_valid = true;
if(strlen($_SESSION['login']) == 0){
    header("Location: index.php");
}
else{
    //Pagename and title
    $pagename="Add Post";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;

    //  Custom CSS
    $csslink = '<script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>';

    
    //  Add a new post
    if(isset($_POST['post-title'], $_POST['submit'])){
        include ('upload-image.php');
        if(strlen($_POST['post-title']) >= 1 && strlen($_POST['post-title']) <= 80){
            $_POST['post-title'] = filter_input(INPUT_POST, 'post-title', FILTER_SANITIZE_SPECIAL_CHARS);
            $_POST['category'] = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $_POST['post_editor'] = filter_input(INPUT_POST, 'post_editor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //  For Query add new post
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "INSERT INTO posts (author_id, category_id, post_title, meta_title, created_at, content, posts_image) 
                        VALUES (:author_id, :category_id, :title, :meta_title, :created_at, :content, :posts_image)";
            // $query = "INSERT INTO category (parent_id, title, content) VALUES (:parent_id, :title, :content)";
            $statement = $db->prepare($query);
            //  Bind values to the parameters
            $statement->bindValue(':author_id', $_SESSION['user_id']);
            $statement->bindValue(':category_id', $_POST['category']);
            $statement->bindValue(':title', $_POST['post-title']);
            $statement->bindValue(':meta_title', $_POST['meta_title']);
            $statement->bindValue(':created_at', date('Y-m-d H:i:s'));
            $statement->bindValue(':content', $_POST['post_editor']);
            $statement->bindValue(':posts_image', $target_file);
             
            //  Execute the INSERT.
            //  execute() will check for possible SQL injection and remove if necessary
            if($statement->execute()){
                echo "<script>alert('Success')</script>";
                header("Location: manage-posts.php");
                exit;
            }
        }
        if(empty($_POST['post-title'])){
            array_push($err_messages, "Title is empty! Please check again");   
            $is_valid = false;
        }
        else if(strlen($_POST['post-title']) > 80){
            array_push($err_messages, "Title is longer than 80 characters, please check it again!");
            $is_valid = false;
        }
        if(!$is_valid){
            foreach($err_messages as $err){
                echo $err,"<br/>";
            }
        } 
    }
    require_once('includes/header.php');
?>

            <!-- Content Row -->
            <div class="row">

                <!-- Add form -->
                <div class="col-md-12">
                    <form name="posts" method="post" enctype="multipart/form-data">
	                <div class="form-group">
	                    <label class="col-md-6">Post Title</label>
	                        <div class="col-md-12">
	                            <input type="text" class="form-control" value="" name="post-title" required>
	                        </div>
	                </div>

                    <div class="form-group">
	                    <label class="col-md-12">Meta Title</label>
	                        <div class="col-md-12">
	                            <input type="text" class="form-control" value="" name="meta_title">
	                        </div>
	                </div>
                    <?php
                        //Fetch data from database into category dropdown
                        $querycategory = "SELECT * from category";
                        $statement1 = $db->prepare($querycategory);
                        $statement1->execute();
                    ?>
                    <div class="form-group">
                        <label class="col-md-6">Category</label>
                        <div class="col-md-6">
                        <select class="custom-select" name="category">
                            <option selected>Choose...</option>
                            <?php while($row = $statement1->fetch()): ?>
                            <option value="<?= $row['id']?>"><?= $row['title']?></option>
                            <?php endwhile?>
                        </select>
                        </div>
                    </div>
                                                        
	                <div class="form-group">
	                    <label class="col-md-6">Post Details</label>
	                    <textarea name="post_editor"></textarea> 
                        <script>
                        CKEDITOR.replace( 'post_editor' );
                        </script>   
	                </div>
                    
                    <div class="form-group">
                        <label class="col-md-6">Feature Image</label>
                        <input type="file" class="form-control" style="height:auto" id="postimage" name="postimage">
                    </div>

                    <div class="form-group">
                            <div class="col-md-2">                                          
                                <button type="submit" class="btn btn-primary" name="submit">
                                    Submit
                                </button>
                            </div>
                    </div>
	                </form>
                </div>               
            </div>
    <?php include ('includes/footer.php') ?>   
<?php } ?>