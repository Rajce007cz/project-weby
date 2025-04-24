<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Sezóna <?= esc($season['year']) ?></h2>

<p><?= esc($season['description']) ?></p>

<a href="<?=base_url("/seasons");?>">Back</a>
<?php 
  $this->endSection();?>