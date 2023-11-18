<?php
include ('includes/config.php');
error_reporting(0);
$err_messages = [];
$is_valid = true;
if(strlen($_SESSION['login']) == 0){
    header("Location: index.php");
}
else{
    $pagename="Add Category";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;
    
    //Add new Category
    if(isset($_POST['category'], $_POST['description'], $_POST['submit'])){
        if(strlen($_POST['category']) >= 1 && strlen($_POST['category']) <= 80){
            $_POST['category'] = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $_POST['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "INSERT INTO category (title, content) VALUES (:title, :content)";
            // $query = "INSERT INTO category (parent_id, title, content) VALUES (:parent_id, :title, :content)";
            $statement = $db->prepare($query);
 
            //  Bind values to the parameters
            // $statement->bindValue(':parent_id', $_POST['parent-category']);
            $statement->bindValue(':title', $_POST['category']);
            $statement->bindValue(':content', $_POST['description']);
             
            //  Execute the INSERT.
            //  execute() will check for possible SQL injection and remove if necessary
            if($statement->execute()){
                echo "<script>alert('Success')</script>";
                header("Location: manage-categories.php");
                exit;
            }
        }
        if(empty($_POST['category'])){
            array_push($err_messages, "Title is empty! Please check again");   
            $is_valid = false;
        }
        else if(strlen($_POST['category']) > 80){
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
                    <form name="category" method="post">
	                <div class="form-group">
	                    <label class="col-md-6">Category Name</label>
	                        <div class="col-md-12">
	                            <input type="text" class="form-control" value="" name="category" required>
	                        </div>
	                </div>
                    <?php
                        $query1 = "SELECT title from category";
                        $statement1 = $db->prepare($query1);
                        $statement1->execute();
                    ?>
                                                        
	                <div class="form-group">
	                    <label class="col-md-6">Category Description</label>
	                        <div class="col-md-12">
	                            <textarea class="form-control" rows="8" name="description" required></textarea>
	                        </div>
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