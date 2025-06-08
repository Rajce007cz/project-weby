<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="/drivers">Drivers</a></li>
  <li class="breadcrumb-item active">Driver Details</li>
</ol>
<div class="container my-4">
    <div class="card mb-4 p-4">
        <div class="row g-4 align-items-center">
            <div class="col-md-3 text-center">
                <?php if ($driver['image']): ?>
                    <img src="<?= base_url("images/" . esc($driver['image'])); ?>" 
                         alt="Driver photo" 
                         class="img-fluid rounded shadow" 
                         style="height: 200px; object-fit: cover;">
                <?php endif; ?>
            </div>
            <div class="col-md-9">
                <h1 class="mb-3"><?= esc($driver['first_name']) . ' ' . esc($driver['last_name']) ?></h1>
                <p><strong>Nationality:</strong> <?= esc($driver['nationality']) ?></p>
                <p><strong>Date of Birth:</strong> <?= esc(formatCzechDate($driver['dob'])) ?></p>
                <p><strong>Points:</strong> <?= esc($driver['points']) ?></p>
                <p><strong>Wins:</strong> <?= esc($driver['win']) ?></p>
                <p><strong>WDC Titles:</strong> <?= esc($driver['wdc']) ?></p>
            </div>
        </div>
    </div>

    <h2 class="mb-3">Statistics</h2>

    <?php if (!empty($seasonResults)) : ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Season</th>
                        <th>Position</th>
                        <th>Points</th>
                        <th>Wins</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seasonResults as $season) : ?>
                        <tr>
                            <td><?= esc($season['year']) ?></td>
                            <td><?= esc($season['season_position']) ?></td>
                            <td><?= esc($season['season_points']) ?></td>
                            <td><?= esc($season['season_wins']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-info">No results available.</div>
    <?php endif; ?>
</div>

<?php 
$this->endSection(); 
?>