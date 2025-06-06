<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Edit driver</h2>
<form action="<?= base_url("/drivers/update/" . $driver['id'])?>" method="post">
    <label>First name: <input type="text" name="first_name" value="<?= esc($driver['first_name']) ?>"></label><br>
    <label>Last name: <input type="text" name="last_name" value="<?= esc($driver['last_name']) ?>"></label><br>
    <label>Nationality: <input type="text" name="nationality" value="<?= esc($driver['nationality']) ?>"></label><br>
    <label>Date of birth: <input type="date" name="dob" value="<?= esc($driver['dob']) ?>"></label><br>
    <button type="submit">Save</button>
</form>
<?php 
  $this->endSection();?>