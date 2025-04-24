<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Add driver</h2>
<form action="/drivers/store" method="post">
    <label>First name: <input type="text" name="first_name"></label><br>
    <label>Last name: <input type="text" name="last_name"></label><br>
    <label>Nationality: <input type="text" name="nationality"></label><br>
    <label>Date of birth: <input type="date" name="dob"></label><br>
    <button type="submit">Save</button>
</form>
<?php 
  $this->endSection();?>