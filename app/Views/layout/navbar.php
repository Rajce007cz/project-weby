<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <?php if (session()->get('user_id')): ?>
        <a href="<?=base_url("/logout");?>">LogOut</a>
    <?php else: ?>
        <a href="<?=base_url("/register");?>">Register</a>
        <a href="<?=base_url("/login");?>">Log In</a>
    <?php endif; ?>
    
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?=base_url("/drivers");?>">F1 Drivers</a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=base_url("/teams");?>">F1 Teams</a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=base_url("/seasons");?>">Seasons</a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=base_url("/seasons/season25");?>">Season 2025</a>
        </li>

    </div>
  </div>
  
</nav>