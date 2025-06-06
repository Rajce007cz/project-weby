<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h1>Season 2025 – races</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color:green"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<?php if (session()->get('user_id')): ?>
    <p><a href="<?= site_url('seasons/season25/add') ?>">Add Race</a></p>
<?php endif; ?>

<?php foreach ($races as $race): ?>
<details style="margin-bottom:1rem">
    <summary style="cursor:pointer;font-weight:bold">
        <?= esc($race['name']) ?> – <?= esc($race['country']) ?> (<?= esc($race['date']) ?>)
    </summary>

    <?php if (!empty($byRace[$race['id']])): ?>
        <?php 
          $rows = $byRace[$race['id']];
          $top3 = array_slice($rows, 0, 3);
          $rest = array_slice($rows, 3);
        ?>

        <!-- Tabulka s prvními 3 jezdci, vždy viditelná -->
        <table cellpadding="4" style="margin-top:6px; width: 100%;" id="top3-<?= $race['id'] ?>">
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

        <?php if (!empty($rest)): ?>
            <!-- Skrytá tabulka se všemi jezdci, včetně těch prvních 3 -->
            <div id="all-drivers-<?= $race['id'] ?>" style="display:none; margin-top:6px;">
                <table cellpadding="4" style="width: 100%;">
                    <thead>
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

            <div style="text-align:center; margin-top: 0.5rem;">
                <button type="button" onclick="toggleAllDrivers('<?= $race['id'] ?>', this)">▼ All drivers</button>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>– No results –</p>
    <?php endif; ?>

    <?php if (session()->get('user_id')): ?>
        <p>
            <a href="<?= site_url('seasons/season25/edit/' . $race['id']) ?>">Edit</a>
            <a href="<?= site_url('seasons/season25/delete/' . $race['id']) ?>" onclick="return confirm('Smazat závod?')">Delete</a>
        </p>
    <?php endif; ?>
</details>
<?php endforeach; ?>

<script>
function toggleAllDrivers(raceId, btn) {
  const container = document.getElementById('all-drivers-' + raceId);
  const top3Table = document.getElementById('top3-' + raceId);
  if (container.style.display === 'none' || container.style.display === '') {
    container.style.display = 'block';
    top3Table.style.display = 'none';  // schovej první 3, protože je už ukazuje celková tabulka
    btn.textContent = '▲ Less 3 drivers';
  } else {
    container.style.display = 'none';
    top3Table.style.display = 'table'; // zobraz první 3 zpět
    btn.textContent = '▼ More drivers';
  }
}
</script>

<?php $this->endSection(); ?>