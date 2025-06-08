<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Seasons</li>
</ol>
<div class="container mt-5">
    <h1 class="mb-4">F1 Seasons</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Year</th>
                <th>Winner</th>
                <th>Team</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seasonData as $s): ?>
                <tr>
                    <td><?= esc($s['year']) ?></td>
                    <td><?= esc($s['winner']) ?></td>
                    <td><?= esc($s['team']) ?></td>
                    <td><a href="<?= base_url("seasons/" . $s['year']) ?>">Show details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php 
  $this->endSection();?>