<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="/drivers">Drivers</a></li>
  <li class="breadcrumb-item active">Edit Driver</li>
</ol>
<h2 class="mb-4 mt-5">Edit driver</h2>
<form action="<?= base_url("/drivers/update/" . $driver['id'])?>" method="post" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="first_name" class="form-label">First name:</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($driver['first_name']) ?>" placeholder="Enter first name">
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last name:</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($driver['last_name']) ?>" placeholder="Enter last name">
    </div>
    <div class="mb-3">
        <label for="nationality" class="form-label">Nationality:</label>
        <input type="text" class="form-control" id="nationality" name="nationality" value="<?= esc($driver['nationality']) ?>" placeholder="Enter nationality">
    </div>
    <div class="mb-3">
        <label for="dob" class="form-label">Date of birth:</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= esc($driver['dob']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<?php 
  $this->endSection();?>