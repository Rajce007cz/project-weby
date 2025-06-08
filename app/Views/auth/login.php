<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">log In</li>
</ol>
<div class="mt-5 d-flex justify-content-center align-items-center">
    <div style="max-width: 400px; width: 100%;">
        <h2 class="mb-4">Log In</h2>

<?php if (session('error')): ?>
    <p class="text-danger"><?= session('error') ?></p>
<?php endif; ?>

<form method="post" action="<?= base_url("/login"); ?>" class="form-group" style="max-width: 400px;">
    <div class="mb-3">
        <input type="text" name="usernameOrEmail" class="form-control" placeholder="Username or Email" required>
    </div>

    <div class="mb-3 position-relative">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 mt-1 me-1" onclick="togglePassword()">
            <i class="fa-regular fa-eye"></i>
        </button>
    </div>

    <button type="submit" class="btn btn-primary">Log In</button>
</form>
</div>
<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    if (pwd.type === "password") {
        pwd.type = "text";
    } else {
        pwd.type = "password";
    }
}
</script>

<?php $this->endSection(); ?>