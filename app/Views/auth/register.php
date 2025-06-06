<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Registrace</h2>
<form method="post" action="<?=base_url("/register");?>">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<?php 
  $this->endSection();?>