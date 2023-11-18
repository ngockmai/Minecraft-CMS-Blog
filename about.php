<?php 
include('admin/includes/config.php');
require_once ('includes/header.php');
// error_reporting(0);
?>
    <!-- Page Content -->
    <div class="container">
      <div class="row" style="margin-top: 4%">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
          <!-- Blog Post -->
          <div class="card mb-4">  
            <div class="card-body">
              <h2 class="card-title">About us</h2>
<!--category-->
<hr>
 <p class="card-text">This is about us page
</p>
             
            </div>
            <div class="card-footer text-muted">
             
           
            </div>
          </div>

        </div>

        <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php');?>
      </div>

    <!-- /.container -->
<?php require_once ('includes/footer.php')?>