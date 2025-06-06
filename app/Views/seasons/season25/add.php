<!DOCTYPE html><html><head>
    <title>Přidat závod – 2025</title>

    <!-- select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style> th,td {padding:4px} </style>
</head><body>

<h2>Přidat závod – sezóna 2025</h2>

<form method="post">
    <?= csrf_field() ?>

    <p>
        <label>Date&nbsp;
            <input type="date" name="date" required>
        </label>
        &nbsp;
        <label>Country&nbsp;
            <input type="text" name="country" required>
        </label>
    </p>

    <p>
        <label>Race Name&nbsp;
            <select id="race_name" name="race_name" style="width:300px" required>
    <option></option>
    <?php foreach ($raceNames as $raceName): ?>
        <option value="<?= esc($raceName) ?>"><?= esc($raceName) ?></option>
    <?php endforeach; ?>
</select>
        </label>
    </p>

    <h3>Positions 1-20</h3>
    <table>
        <thead>
            <tr>
                <th>Position</th><th>Driver</th><th>Team</th><th>Points</th>
            </tr>
        </thead>
        <tbody>
        <?php for($i=1;$i<=20;$i++): ?>
            <tr>
                <td>
                    <?= $i ?>
                    <input type="hidden" name="position[]" value="<?= $i ?>">
                </td>
                <td>
                    <select class="driver-select" name="driver_id[]" style="width:180px">
                        <option value="">–</option>
                        <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d['id'] ?>">
                                <?= esc($d['last_name'].' '.$d['first_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select class="team-select" name="team_id[]" style="width:150px">
                        <option value="">–</option>
                        <?php foreach ($teams as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="points[]" step="0.5" min="0" style="width:70px"></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <p><button type="submit">Save race</button>
       <a href="<?= base_url('seasons/season25') ?>">Back</a></p>
</form>

<script>
$(function () {
    $('#race_name').select2({placeholder:'Vyber závod',allowClear:true});
    $('.driver-select').select2({placeholder:'Jezdec',allowClear:true});
    $('.team-select').select2({placeholder:'Tým',allowClear:true});
});
</script>

</body></html>