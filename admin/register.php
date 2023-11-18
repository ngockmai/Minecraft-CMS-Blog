<?php
//Connect database
require_once ('includes/config.php');

//Add new user
if(isset($_POST['email'], $_POST['password1'], $_POST['submit'])){
    $_POST['first_name'] = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_POST['last_name'] = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)){
        $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    }
    $_POST['password1'] = md5($_POST['password1']);
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO users (first_name, last_name, email, user_password, registered_at, role_id, active) 
                    VALUES (:first_name, :last_name, :email, :user_password, :registered_at, 0, 1)";

        $statement = $db->prepare($query);
        

        //  Bind values to the parameters
        // $statement->bindValue(':parent_id', $_POST['parent-category']);
        $statement->bindValue(':first_name', $_POST['first_name']);
        $statement->bindValue(':last_name', $_POST['last_name']);
        $statement->bindValue(':email', $_POST['email']);
        $statement->bindValue(':user_password', $_POST['password1']);
        $statement->bindValue(':registered_at', date('Y-m-d H:i:s'));
        // $statement->bindValue(':role_id', $_POST['role_id'], PDO::PARAM_INT);
        
        print_r($statement->fetch());
        //  Execute the INSERT.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            echo "<script>alert('Success')</script>";
            header("Location: index.php");
            exit;
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
        <!-- Custom fonts for this template-->
        <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script>
          
            // Function to check Whether both passwords
            // is same or not.
            function checkPassword(form) {
                password1 = form.password1.value;
                password2 = form.password2.value;
  
                // If password not entered
                if (password1 == '')
                    alert ("Please enter Password");
                      
                // If confirm password not entered
                else if (password2 == '')
                    alert ("Please enter confirm password");
                      
                // If Not same return False.    
                else if (password1 != password2) {
                    alert ("\nPassword did not match: Please try again...")
                    return false;
                }
  
                // If same return True.
                else{
                    return true;
                    alert("Email registered. Come back to login page!")
                }
            }
        </script>
</head>
<body class="bg-gradient-primary">
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form onsubmit="return checkPassword(this)" class="user" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="first_name"
                                            placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="last_name"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email"
                                        placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            name="password1" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            name="password2" placeholder="Confirm Password">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block" name="submit">Register Account</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="index.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
   <!-- Bootstrap core JavaScript-->
   <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>