<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Drivers</li>
                    </ol>
                </nav>
<h2>F1 Drivers</h2>

<div style="margin-bottom: 15px;">
    <a href="<?= base_url("/drivers/create"); ?>">+ Add Driver</a> | 
    <a href="<?= base_url("/drivers/trashed"); ?>"> Deleted drivers</a>
</div>

<div class="row">
    <?php foreach ($drivers as $d): ?>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></h5>
                    <img class="card-img-top" src="<?= base_url("images/" . esc($d['image'])); ?>"
                         onerror="this.onerror=null; this.src='/images/drivers/default.png';">
                    <p class="card-text"><strong>WDC:</strong> <?= esc($d['wdc']) ?></p>
                    <p class="card-text"><strong>Wins:</strong> <?= esc($d['win']) ?></p>
                    <p class="card-text"><strong>Points:</strong> <?= esc($d['points']) ?></p>
                    <p class="card-text"><strong>Nationality:</strong> <?= esc($d['nationality']) ?></p>
                    <p class="card-text"><strong>Date of birth:</strong> <?= esc($d['dob']) ?></p>
                    <div class="card-actions">
                        <a href="<?= base_url("/drivers/edit/" . $d['id']); ?>">Edit</a> |
                        <a href="<?= base_url("/drivers/delete/" . $d['id']); ?>" onclick="return confirm('Delete this driver?')">Delete</a>
                    </div>
                </div>                 
            </div>  
        </div>    
        
    <?php endforeach; ?>  
            <?php echo $pager->links();?>
</div>

<?php 
$this->endSection(); 
?>