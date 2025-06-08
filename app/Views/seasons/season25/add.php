<?= $this->extend("layout/layout") ?>
<?= $this->section("content") ?>

<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="/seasons/season25">Season 2025</a></li>
  <li class="breadcrumb-item active">Add 2025 Race</li>
</ol>
<h2 class="mb-4 mt-3">Add race to 2025 F1 Season</h2>

<form method="post" class="w-75 mx-auto">
    <?= csrf_field() ?>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label for="country" class="form-label">Country</label>
            <select id="country" name="country" class="form-select" required>
                <option value=""></option>
                <?php foreach ($raceCountries as $country): ?>
                    <option value="<?= esc($country) ?>"><?= esc($country) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="race_name" class="form-label">Race Name</label>
            <select id="race_name" name="race_name" class="form-select" required>
                <option value=""></option>
                <?php foreach ($raceNames as $raceName): ?>
                    <option value="<?= esc($raceName) ?>"><?= esc($raceName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <h3 class="mb-3">Positions 1-20</h3>
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
                <?php for ($i = 1; $i <= 20; $i++): ?>
                    <tr>
                        <td>
                            <?= $i ?>
                            <input type="hidden" name="position[]" value="<?= $i ?>">
                        </td>
                        <td>
                            <select class="form-select driver-select" name="driver_id[]" style="width: 180px;">
                                <option value="">–</option>
                                <?php foreach ($drivers as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= esc($d['last_name'] . ' ' . $d['first_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-select team-select" name="team_id[]" style="width: 150px;">
                                <option value="">–</option>
                                <?php foreach ($teams as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="points[]" step="0.5" min="0" class="form-control" style="width: 70px;">
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>


    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Save race</button>
        <a href="<?= base_url('seasons/season25') ?>" class="btn btn-secondary ms-2">Back</a>
    </div>
</form>

<script>
$(function () {
    $('#race_name').select2({placeholder:'Vyber závod',allowClear:true});
    $('.driver-select').select2({placeholder:'Jezdec',allowClear:true});
    $('.team-select').select2({placeholder:'Tým',allowClear:true});
});
</script>
<?= $this->endSection() ?>
