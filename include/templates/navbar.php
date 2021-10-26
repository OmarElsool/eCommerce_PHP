<?php

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Home Page</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php
          $allCat = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
          foreach($allCat as $cat){
              echo '<li class="nav-item">
              <a class="nav-link active" href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a>
              </li>';
          }
        ?>
    </ul>
</div>
  </div>
</nav>