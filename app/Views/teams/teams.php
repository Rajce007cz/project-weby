<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Teams</li>
</ol>
    <div class="container mt-5">
        <h1 class="mb-4">Teams</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Team name</th>
                    <th>Nationality</th>
                    <th>Points</th>
                    <th>Wins</th>
                    <th>WCC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $index => $team): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($team['name']) ?></td>
                        <td><?= esc($team['nationality']) ?></td>
                        <td><?= esc($team['points']) ?></td>
                        <td><?= esc($team['wins']) ?></td>
                        <td><?= esc($team['wcc']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php 
$this->endSection(); 
?>