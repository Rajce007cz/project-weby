<?= $this->extend("layout/layout") ?>
<?= $this->section("content") ?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="/seasons/season25">Season 2025</a></li>
  <li class="breadcrumb-item active">Edit 2025 Race</li>
</ol>
<h1>Upravit závod</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color:green"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<form method="post" action="" class="w-75 mx-auto">
    <div class="mb-3">
        <label for="race_name" class="form-label">Race Name</label>
        <select name="race_name" id="race_name" class="form-select" required>
            <?php foreach ($raceNames as $name): ?>
                <option value="<?= esc($name) ?>" <?= ($race['name'] === $name) ? 'selected' : '' ?>>
                    <?= esc($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" id="country" name="country" class="form-control" value="<?= esc($race['country']) ?>" required>
    </div>

    <div class="mb-4">
        <label for="date" class="form-label">Date</label>
        <input type="date" id="date" name="date" class="form-control" value="<?= esc($race['date']) ?>" required>
    </div>

    <h3 class="mb-3">Finish order</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Position</th>
                    <th>Driver</th>
                    <th>Team</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 20; $i++):
                    $result = $results[$i] ?? null;
                ?>
                <tr>
                    <td style="width: 70px;">
                        <input type="number" name="position[]" class="form-control" value="<?= esc($result['position'] ?? ($i+1)) ?>" min="1" max="20" required>
                    </td>
                    <td>
                        <select name="driver_id[]" class="form-select" required>
                            <option value="">-- Choose Driver --</option>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?= $driver['id'] ?>" <?= ($result && $result['driver_id'] == $driver['id']) ? 'selected' : '' ?>>
                                    <?= esc($driver['first_name'] . ' ' . $driver['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="team_id[]" class="form-select" required>
                            <option value="">-- Choose Team --</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= $team['id'] ?>" <?= ($result && $result['team_id'] == $team['id']) ? 'selected' : '' ?>>
                                    <?= esc($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td style="width: 90px;">
                        <input type="number" name="points[]" class="form-control" value="<?= esc($result['points'] ?? 0) ?>" min="0" max="100">
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= site_url('seasons/season25') ?>" class="btn btn-secondary ms-2">Back</a>
    </div>
</form>

<?= $this->endSection() ?>