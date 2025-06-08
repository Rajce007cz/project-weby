
<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="/teams">Teams</a></li>
  <li class="breadcrumb-item active">Team Seasons</li>
</ol>
<div class="container my-4">
    <h2 class="mb-4">Team Results – <?= esc($team->name) ?> in Season <?= esc($year) ?></h2>

    <h3 class="mb-3">Driver Results in Team</h3>
    <div class="table-responsive mb-4">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Driver</th>
                    <th>Points</th>
                    <th>Wins</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seasonResults as $driver): ?>
                    <tr>
                        <td>
                            <a href="<?= site_url('drivers/driver_details/' . $driver['driver_id']) ?>">
                                <?= esc($driver['first_name'] . ' ' . $driver['last_name']) ?>
                            </a>
                        </td>
                        <td><?= esc($driver['total_points']) ?></td>
                        <td><?= esc($driver['wins']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3 class="mb-3">Race Results</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Driver</th>
                    <?php foreach ($raceList as $race): ?>
                        <th class="text-center"><?= esc($race['name']) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($driverNames as $driverId => $driverName): ?>
                    <tr>
                        <td><?= esc($driverName) ?></td>
                        <?php foreach ($raceList as $race): ?>
                            <?php
                                $entry = $resultsByDriver[$driverId][$race['id']] ?? null;
                                $position = $entry['position'] ?? null;
                                $points = $entry['points'] ?? null;
                            ?>
                            <td class="text-center">
                                <?php if ($entry): ?>
                                    <?= esc($position) ?>.<br>
                                    <small class="text-muted"><?= esc($points) ?> pts</small>
                                <?php else: ?>
                                    <span class="text-muted">–</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php 
$this->endSection(); 
?>