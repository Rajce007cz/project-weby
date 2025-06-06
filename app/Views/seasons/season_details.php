<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Sezóna <?= esc($season['year']) ?></h2>

<p><?= esc($season['description']) ?></p>

<table class="table">
    <thead>
        <tr>
            <th>Position</th>
            <th>Driver Name</th>
            <th>Team</th>
            <th>Points</th>
            <th>Wins</th>
        </tr>
    </thead>
    <tbody>
        <?php $rank = 1; ?>
    <?php foreach ($results as $driver): ?>
        <tr>
            <td><?= $rank++ ?>.</td>
            <td>
                <a href="<?= base_url("/drivers/driver_details/" . $driver['id']); ?>">
                <?= esc($driver['first_name']) . ' ' . esc($driver['last_name']) ?></a>
            </td>
            <td><?= esc($driver['team_name']) ?></td>
            <td><?= esc($driver['total_points']) ?></td>
            <td><?= esc($driver['wins']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h2 class="mt-5">Team Results</h2>
<table class="table">
    <thead>
        <tr>
            <th>Position</th>
            <th>Team</th>
            <th>Points</th>
            <th>Wins</th>
        </tr>
    </thead>
    <tbody>
        <?php $rank = 1; ?>
        <?php foreach ($teamResults as $team): ?>
            <tr>
                <td><?= $rank++ ?>.</td>

                <td>
                    <a href="<?= base_url('/teams/' . $team['id'] . '/seasons/' . $year) ?>">
                        <?= esc($team['name']) ?>
                    </a>
                </td>
                <td><?= esc($team['total_points']) ?></td>
                <td><?= esc($team['wins']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<a href="<?=base_url("/seasons");?>">Back</a>
<?php 
  $this->endSection();?>