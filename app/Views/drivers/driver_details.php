<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<h1><?= esc($driver['first_name']) . ' ' . esc($driver['last_name']) ?></h1>
<p>Nationality: <?= esc($driver['nationality']) ?></p>
<p>Date of Birth: <?= esc($driver['dob']) ?></p>
<p>Points: <?= esc($driver['points']) ?></p>
<p>Wins: <?= esc($driver['win']) ?></p>
<p>WDC: <?= esc($driver['wdc']) ?></p>

<?php if ($driver['image']): ?>
    <img style="height: 200px; object-fit: cover;"  src="<?= base_url("images/" . esc($driver['image'])); ?>"
<?php endif; ?>

<h2>Statistics</h2>

<?php if (!empty($seasonResults)) : ?>
    <table class="table">
        <thead>
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
<?php else : ?>
    <p>No results avaible.</p>
<?php endif; ?>

<?php 
$this->endSection(); 
?>