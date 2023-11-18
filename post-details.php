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

            <?php
            $pid = intval($_GET['nid']);
            $currenturl="http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;
            $query = "SELECT u.email, p.id as pid, p.post_title as posttitle, p.posts_image as postimage, p.content as postcontent, p.created_at as postingdate, c.id as cid, c.title as category
            FROM users as u
                JOIN  posts as p
                ON u.id = p.author_id
                JOIN category as c 
                ON p.category_id = c.id 
            WHERE p.id = :pid";
            $statement = $db->prepare($query);
            $statement->bindValue(':pid', $pid);
            $statement->execute();
            while ($row = $statement->fetch()) {
            ?>

          <div class="card mb-4">
      
            <div class="card-body">
              <h2 class="card-title"><?php echo htmlspecialchars_decode($row['posttitle']);?></h2>
<!--category-->
 <a class="badge bg-secondary text-decoration-none link-light" href="category.php?catid=<?php echo htmlentities($row['cid'])?>" style="color:#fff"><?php echo htmlentities($row['category']);?></a>
<p> 
          <b>Posted by </b> <?php echo htmlentities($row['email']);?> on </b><?php echo htmlentities($row['postingdate']);?>
                <p><strong>Share:</strong> <a href="http://www.facebook.com/share.php?u=<?php echo $currenturl;?>" target="_blank">Facebook</a> | 
<a href="https://twitter.com/share?url=<?php echo $currenturl;?>" target="_blank">Twitter</a> |
<a href="https://web.whatsapp.com/send?text=<?php echo $currenturl;?>" target="_blank">Whatsapp</a> | 
<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $currenturl;?>" target="_blank">Linkedin</a>
                </p>
                <hr />
 <img class="img-fluid rounded" src="./admin/<?php echo htmlentities($row['postimage']);?>" alt="<?php echo htmlspecialchars_decode($row['posttitle']);?>">
  
              <p class="card-text"><?php 
$pt=$row['postcontent'];
              echo  htmlspecialchars_decode(substr($pt,0));?></p>
             
            </div>
            <div class="card-footer text-muted">
             
           
            </div>
          </div>
<?php } ?>

        </div>

        <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php');?>
      </div>

    <!---Comment Section --->

    <div class="row" style="margin-top: -8%">
        <div class="col-md-8">
            <div class="card my-4">
                <h5 class="card-header">Leave a Comment:</h5>
                <div class="card-body">
                    <form name="Comment" method="post">
                        <input type="hidden" name="csrftoken" value="<?php echo htmlentities($_SESSION['token']); ?>" />
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Enter your fullname" required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Enter your Valid email" required>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Comment" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </form>
                </div>
            </div>
  <!---Comment Display Section --->
        <?php 
        $sts=1;
        $query1 = "SELECT u.email, cmt.title, cmt.content, cmt.created_at 
        FROM users as u
            JOIN posts as p ON u.id = p.author_id 
            JOIN post-comments as cmt ON p.id = cmt.post_id 
        WHERE p.id = :pid";
        $statement1 = $db->prepare($query1);
        $statement1->bindValue(':pid',$pid);
        while ($row = $statement1->fetch()) {
        ?>
            <div class="media mb-4">
                <img class="d-flex mr-3 rounded-circle" src="images/usericon.png" alt="">
                <div class="media-body">
                    <h5 class="mt-0"><?php echo htmlentities($row['title']);?> <br />
                        <span style="font-size:11px;"><b>at</b> <?php echo htmlentities($row['created_at']);?></span>
                    </h5>
                <?php echo htmlentities($row['content']);?>            
                </div>
            </div>
        <?php } ?>
            </div>
        </div>
    </div>
    <!-- /.container -->
<?php require_once ('includes/footer.php')?>