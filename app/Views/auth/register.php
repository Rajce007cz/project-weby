<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Register</li>
</ol>
<div class="mt-5 d-flex justify-content-center align-items-center">
    <div style="max-width: 400px; width: 100%;">
        <h2 class="mb-4">Register</h2>

        <?php if (isset($validation)): ?>
            <div class="text-danger mb-3">
                <?= $validation->listErrors(); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url("/register"); ?>" class="form-group">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" value="<?= old('username') ?>" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>