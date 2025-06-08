<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Season 2025</li>
</ol>
<h1 class="mb-4 mt-5">Season 2025 – races</h1>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<?php if (session()->get('user_id')): ?>
    <p><a href="<?= site_url('seasons/season25/add') ?>" class="btn btn-primary btn-sm">Add Race</a></p>
<?php endif; ?>

<?php foreach ($races as $race): ?>
<div class="card mb-4 shadow-sm">
    <div class="card-header fw-bold bg-primary">
        <?= esc($race['name']) ?> – <?= esc($race['country']) ?> (<?= esc(formatCzechDate($race['date']))?>)
    </div>

    <div class="card-body">
        <?php if (!empty($byRace[$race['id']])): ?>
            <?php 
              $rows = $byRace[$race['id']];
              $top3 = array_slice($rows, 0, 3);
              $rest = array_slice($rows, 3);
            ?>

            <!-- Top 3 -->
            <div>
                <table class="table table-stripped" id="top3-<?= $race['id'] ?>" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 55%;">
                        <col style="width: 30%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Pos.</th><th>Driver</th><th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top3 as $row): ?>
                            <tr>
                                <td><?= $row['position'] ?>.</td>
                                <td><?= esc($row['first_name'].' '.$row['last_name']) ?></td>
                                <td><?= $row['points'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($rest)): ?>
                <!-- All drivers hidden initially -->
                <div id="all-drivers-<?= $race['id'] ?>" style="display:none; margin-bottom: 1rem;">
                    <table class="table table-stripped" style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 55%;">
                            <col style="width: 30%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>Pos.</th><th>Driver</th><th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= $row['position'] ?>.</td>
                                    <td><?= esc($row['first_name'].' '.$row['last_name']) ?></td>
                                    <td><?= $row['points'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <button class="btn btn-sm" type="button" onclick="toggleAllDrivers('<?= $race['id'] ?>', this)">
                        ▼ More drivers
                    </button>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>– No results –</p>
        <?php endif; ?>

        <?php if (session()->get('user_id')): ?>
            <div class="mt-3">
                <a href="<?= site_url('seasons/season25/edit/' . $race['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= site_url('seasons/season25/delete/' . $race['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Smazat závod?')">Delete</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<script>
function toggleAllDrivers(raceId, btn) {
  const container = document.getElementById('all-drivers-' + raceId);
  const top3Table = document.getElementById('top3-' + raceId);
  const isHidden = container.style.display === 'none' || container.style.display === '';
  
  container.style.display = isHidden ? 'table' : 'none';
  top3Table.style.display = isHidden ? 'none' : 'table';
  btn.textContent = isHidden ? '▲ Less drivers' : '▼ More drivers';
}
</script>
<?php $this->endSection(); ?>