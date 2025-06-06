<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Přihlášení</h2>
<?php if (session('error')): ?>
    <p style="color: red;"><?= session('error') ?></p>
<?php endif; ?>
<form method="post" action="<?=base_url("/login");?>">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Log In</button>
</form>
<?php 
  $this->endSection();?>