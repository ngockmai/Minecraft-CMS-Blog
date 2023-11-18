<div class="col-md-4">

<!-- Search Widget -->
<div class="card mb-4">
  <h5 class="card-header">Search</h5>
  <div class="card-body">
         <form name="searchtitle" action="search.php" method="post">
    <div class="input-group">
<input type="text" name="searchtitle" class="form-control" placeholder="Search for..." required>
      <span class="input-group-btn">
        <button class="btn btn-secondary" type="submit">Go!</button>
      </span>
    </form>
    </div>
  </div>
</div>

<!-- Categories Widget -->
<div class="card my-4">
  <h5 class="card-header">Categories</h5>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-6">
        <ul class="list-unstyled mb-0">
          <?php 
          $query = "SELECT id, title, content FROM category WHERE active = 1";
          $statement = $db->prepare($query);
          $statement->execute();
          while($row = $statement->fetch())
          {
          ?>
          <li>
            <a href="category.php?catid=<?php echo htmlentities($row['id'])?>"><?php echo $row['title'];?></a>
          </li>
<?php } ?>
        </ul>
      </div>

    </div>
  </div>
</div>

<!-- Side Widget -->
<div class="card my-4">
  <h5 class="card-header">Recent News</h5>
  <div class="card-body">
    <ul class="mb-0">
<?php
$query1 = "SELECT p.id as pid, p.post_title as posttitle
FROM posts as p
WHERE p.active = 1
LIMIT 8";
$statement1 = $db->prepare($query1);
$statement1->execute();
while ($row = $statement1->fetch()) {
?>
      <li>
        <a href="post-details.php?nid=<?php echo htmlentities($row['pid'])?>"><?php echo htmlspecialchars_decode($row['posttitle']);?></a>
      </li>
<?php } ?>
    </ul>
  </div>
</div>


</div>
