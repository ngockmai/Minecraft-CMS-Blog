<?php
include('admin/includes/config.php');

//Pagename and title
$titletag = "Minecraft - Homepage";
require_once ('includes/header.php');
?>
    <!-- Page Content -->
    <div class="container">
      <div class="row" style="margin-top: 4%">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
          <!-- Blog Post -->
<?php
     if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 8;
        $offset = ($pageno-1) * $no_of_records_per_page;

        $query_total_pages = "SELECT COUNT(*) FROM posts";
        $statement = $db->prepare($query_total_pages);
        $statement->execute();
        $total_rows = $statement->fetch();
        // $total_rows = mysqli_fetch_array($result);
        $total_pages = ceil($total_rows[0] / $no_of_records_per_page);

//  Query information to display homepage
$query = "SELECT p.id as pid, p.post_title as posttitle, p.posts_image as postimage, p.content as postcontent, p.created_at as postingdate, c.id as cid, c.title as category
            FROM posts as p
            LEFT JOIN category as c
                ON p.category_id = c.id
            WHERE p.active = 1
            ORDER BY p.id DESC
            LIMIT $offset, $no_of_records_per_page";
$statement1 = $db->prepare($query);
$statement1->execute();
while ($row = $statement1->fetch()) {
?>
          <div class="card mb-4">
 <img class="card-img-top" src="admin/<?php echo htmlentities($row['postimage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>">
            <div class="card-body">
              <h2 class="card-title"><?php echo htmlentities($row['posttitle']);?></h2>
            <!--category-->
 <a class="badge bg-secondary text-decoration-none link-light" href="category.php?catid=<?php echo htmlentities($row['cid'])?>" style="color:#fff"><?=$row['category']?></a>
              <a href="post-details.php?nid=<?php echo htmlentities($row['pid'])?>" class="btn btn-primary">Read More &rarr;</a>
            </div>
            <div class="card-footer text-muted">
              Posted on <?php echo htmlentities($row['postingdate']);?>
            </div>
          </div>
<?php } ?>
          <!-- Pagination -->

    <ul class="pagination justify-content-center mb-4">
        <li class="page-item"><a href="?pageno=1"  class="page-link">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?> page-item">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>" class="page-link">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?> page-item">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?> " class="page-link">Next</a>
        </li>
        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
    </ul>

        </div>

        <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php');?>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
<?php require_once ('includes/footer.php')?>