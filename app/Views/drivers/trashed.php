<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<h2>Deleted drivers</h2>

<div style="margin-bottom: 15px;">
    <a href="<?= base_url("/drivers"); ?>">← Drivers</a>
</div>

<div class="row">
    <?php if (!empty($drivers)): ?>
        <?php foreach ($drivers as $d): ?>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></h5>
                        <img class="card-img-top" src="<?= base_url("images/" . esc($d['image'])); ?>"
                             onerror="this.onerror=null; this.src='/images/drivers/default.png';">
                        <p class="card-text"><strong>Nationality:</strong> <?= esc($d['nationality']) ?></p>
                        <p class="card-text"><strong>Date of birth:</strong> <?= esc($d['dob']) ?></p>
                        <?php if (session()->get('user_id')): ?>
                        <div class="card-actions">
                            <a href="<?= base_url("/drivers/restore/" . $d['id']); ?>">Restore</a> |
                            <a href="<?= base_url("/drivers/forceDelete/" . $d['id']); ?>" onclick="return confirm('Opravdu trvale smazat?')">Delete</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>    
        <?php endforeach; ?>
    <?php else: ?>
        <p>No deleted drivers.</p>
    <?php endif; ?>
</div>

<?php 
$this->endSection(); 
?>