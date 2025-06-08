<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">

    <!-- Toggler button pro mobil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible menu -->
    <div class="collapse navbar-collapse ml-5" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/"); ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/drivers"); ?>">F1 Drivers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/teams"); ?>">F1 Teams</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/seasons"); ?>">Seasons</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/seasons/season25"); ?>">Season 2025</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="<?= base_url("/export"); ?>">Export</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white" href="https://github.com/Rajce007cz/project-weby" target="_blank" rel="noopener noreferrer">GitHub</a>
        </li>
      </ul>

      <div class="d-flex gap-2">
        <?php if (session()->get('user_id')): ?>
          <a class="btn btn-outline-light" href="<?= base_url("/logout"); ?>">LogOut</a>
        <?php else: ?>
          <a class="btn btn-outline-light" href="<?= base_url("/register"); ?>">Register</a>
          <a class="btn btn-outline-light" href="<?= base_url("/login"); ?>">Log In</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<style>
  .white {
    color: rgb(255, 255, 255);
  }
</style>