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

<?php if (session()->get('user_id')): ?>
<div style="margin-bottom: 15px;">
    <a href="<?= base_url("/drivers/create"); ?>">+ Add Driver</a> | 
    <a href="<?= base_url("/drivers/trashed"); ?>"> Deleted drivers</a>
</div>
<?php endif; ?>

<div class="row">
    <?php foreach ($drivers as $d): ?>
        <div class="col-4 p-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><a href="<?= base_url("/drivers/driver_details/" . $d['id']); ?>">
                    <?= esc($d['first_name']) . ' ' . esc($d['last_name']) ?></a></h5>
                    <img class="card-img-top" style="height: 200px; object-fit: cover;"  src="<?= base_url("images/" . esc($d['image'])); ?>"
                         onerror="this.onerror=null; this.src='/images/drivers/default.png';">
                    <p class="card-text"><strong>WDC:</strong> <?= esc($d['wdc']) ?></p>
                    <p class="card-text"><strong>Wins:</strong> <?= esc($d['win']) ?></p>
                    <p class="card-text"><strong>Points:</strong> <?= esc($d['points']) ?></p>
                    <p class="card-text"><strong>Nationality:</strong> <?= esc($d['nationality']) ?></p>
                    <p class="card-text"><strong>Date of birth:</strong> <?= esc($d['dob']) ?></p>
                    <?php if (session()->get('user_id')): ?>
                    <div class="card-actions">
                        <a href="<?= base_url("/drivers/edit/" . $d['id']); ?>">Edit</a> |
                        <a href="<?= base_url("/drivers/delete/" . $d['id']); ?>" onclick="return confirm('Delete this driver?')">Delete</a>
                    </div>
                    <?php endif; ?>
                </div>                 
            </div>  
        </div>      
    <?php endforeach; ?>  
            <?php echo $pager->links()?>
</div>

<?php 
$this->endSection(); 
?>