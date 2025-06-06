<?= $this->extend("layout/layout") ?>
<?= $this->section("content") ?>

<h1>Upravit závod</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color:green"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<form method="post" action="">
    <label for="race_name">Race Name:</label><br>
    <select name="race_name" id="race_name" required>
        <?php foreach ($raceNames as $name): ?>
            <option value="<?= esc($name) ?>" <?= ($race['name'] === $name) ? 'selected' : '' ?>>
                <?= esc($name) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="country">Country:</label><br>
    <input type="text" id="country" name="country" value="<?= esc($race['country']) ?>" required><br><br>

    <label for="date">Date:</label><br>
    <input type="date" id="date" name="date" value="<?= esc($race['date']) ?>" required><br><br>

    <h3>Finish order</h3>
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Driver</th>
                <th>Team</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < 20; $i++):
                $result = $results[$i] ?? null;
            ?>
            <tr>
                <td>
                    <input type="number" name="position[]" value="<?= esc($result['position'] ?? ($i+1)) ?>" min="1" max="20" required style="width:50px;">
                </td>
                <td>
                    <select name="driver_id[]" required>
                        <option value="">-- Choose Driver --</option>
                        <?php foreach ($drivers as $driver): ?>
                            <option value="<?= $driver['id'] ?>" <?= ($result && $result['driver_id'] == $driver['id']) ? 'selected' : '' ?>>
                                <?= esc($driver['first_name'] . ' ' . $driver['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="team_id[]" required>
                        <option value="">-- Choose Team --</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= $team['id'] ?>" <?= ($result && $result['team_id'] == $team['id']) ? 'selected' : '' ?>>
                                <?= esc($team['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="points[]" value="<?= esc($result['points'] ?? 0) ?>" min="0" max="100" style="width:60px;">
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <br>
    <button type="submit">Save</button>
    <a href="<?= site_url('seasons/season25') ?>">Back</a>
</form>

<?= $this->endSection() ?>