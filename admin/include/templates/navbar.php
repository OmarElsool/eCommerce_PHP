<?php

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home_Admin'); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="categories.php"><?php echo lang('Categories'); ?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="items.php"><?php echo lang('Items'); ?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="members.php"><?php echo lang('Members'); ?></a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="comments.php"><?php echo lang('Comments'); ?></a></li>
    </ul>
    <li class="nav-item dropdown" style="list-style: none">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo $_SESSION['username']; ?>
      </a>
      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="../index.php">Visit Shop</a></li>
        <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      </ul>
    </li>
</div>
  </div>
</nav>