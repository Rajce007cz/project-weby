
<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<h2>Team results <?= esc($team->name) ?> v sezóně <?= esc($year) ?></h2>

<h3>Driver results in team</h3>
<table cellpadding="5">
    <thead>
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

<h3>Race results</h3>
<table cellpadding="5">
    <thead>
        <tr>
            <th>Driver</th>
            <?php foreach ($raceList as $race): ?>
                <th><?= esc($race['name']) ?></th>
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
                    <td style="text-align: center;">
                        <?php if ($entry): ?>
                            <?= esc($position) ?>.<br>
                            <?= esc($points) ?>
                        <?php else: ?>
                            –
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php 
$this->endSection(); 
?>