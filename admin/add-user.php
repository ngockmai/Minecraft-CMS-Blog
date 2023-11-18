<?php
include ('includes/config.php');
error_reporting(0);
$err_messages = [];
$is_valid = true;
if(strlen($_SESSION['login']) == 0){
    header("Location: index.php");
}
else{
    $pagename="Add User";
    $sitename="Minecraft Portal";
    $titletag=$pagename." - ".$sitename;
    
    //Add new user
    if(isset($_POST['email'], $_POST['password'], $_POST['submit'])){
        $_POST['firstname'] = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_POST['lastname'] = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)){
            $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        }
        $_POST['password'] = md5($_POST['password']);
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "INSERT INTO users (first_name, last_name, email, user_password, registered_at, role_id) 
                        VALUES (:first_name, :last_name, :email, :user_password, :registered_at, :role_id)";

            $statement = $db->prepare($query);
            
 
            //  Bind values to the parameters
            // $statement->bindValue(':parent_id', $_POST['parent-category']);
            $statement->bindValue(':first_name', $_POST['firstname']);
            $statement->bindValue(':last_name', $_POST['lastname']);
            $statement->bindValue(':email', $_POST['email']);
            $statement->bindValue(':user_password', $_POST['password']);
            $statement->bindValue(':registered_at', date('Y-m-d H:i:s'));
            $statement->bindValue(':role_id', $_POST['role_id'], PDO::PARAM_INT);
            
            print_r($statement->fetch());
            //  Execute the INSERT.
            //  execute() will check for possible SQL injection and remove if necessary
            if($statement->execute()){
                echo "<script>alert('Success')</script>";
                header("Location: manage-users.php");
                exit;
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
	                    <label class="col-md-6">First Name:</label>
	                        <div class="col-md-6">
	                            <input type="text" class="form-control" value="" name="firstname">
	                        </div>
                            <label class="col-md-6">Last Name:</label>
	                        <div class="col-md-6">
	                            <input type="text" class="form-control" value="" name="lastname">
	                        </div>
	                </div>
                    <div class="form-group">
	                    <label class="col-md-6">Email:</label>
	                        <div class="col-md-6">
	                            <input type="text" class="form-control" value="" name="email" required>
	                        </div>
	                </div>
                                                        
	                <div class="form-group">
	                    <label class="col-md-6">Password:</label>
	                        <div class="col-md-6">
	                            <input type="password" class="form-control" value="" name="password" required>
	                        </div>
	                </div>

                    <div class="form-group">
                        <label class="col-md-3">Role:</label>
                        <div class="col-md-3">
                        <select class="custom-select" name="role_id">                      
                            <option selected value="0">User</option>
                            <option value="1">Admin</option>
                            
                        </select>
                        </div>
                    </div>

                    <div class="form-group">
                            <div class="col-md-2">                                          
                                <button type="submit" class="btn btn-primary" name="submit">
                                    Register
                                </button>
                            </div>
                    </div>
                    <footer>Already a member? <a href="index.php">Login here</a></footer>
	                </form>
                </div>               
            </div>
    <?php include ('includes/footer.php') ?>   
<?php } ?>