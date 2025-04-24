<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>F1 Drivers</h2>
<a href=<?=base_url("/drivers/create");?>>+ Add Driver</a>
<div class="row">
    
    <?php foreach ($drivers as $d): ?>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title"><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></h5>
                <img class="card-img-top" src="<?=base_url("images/".esc($d['image']));?>"
                onerror="this.onerror=null; this.src='/images/drivers/default.png';" >
                <p class="card-text">WDC:</strong> <?= esc($d['wdc']) ?></p>
                <p class="card-text">Wins:</strong> <?= esc($d['win']) ?></p>
                <p class="card-text">Points:</strong> <?= esc($d['points']) ?></p>
                <p class="card-text">Nationality:</strong> <?= esc($d['nationality']) ?></p>
                <p class="card-text">Date of birth:</strong> <?= esc($d['dob']) ?></p>
                <div class="card-actions">
                    <a href="<?=base_url("/drivers/edit/".$d['id']);?>">Edit</a>
                    <a href="<?=base_url("/drivers/delete/".$d['id']);?>" onclick="return confirm('Delete this driver?')">Delete</a>
                </div>
                </div>                 
            </div>  
        </div>    
    <?php endforeach; ?>  
</div>
<?php 
  $this->endSection();?>