<?php
$this->extend("layout/layout");
$this->section("content"); ?>
<h2>Sezóny Formule 1</h2>
<table cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Year</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($seasons as $s): ?>
        <tr>
            <td><?= esc($s['year']) ?></td>
            <td><a href="<?=base_url("seasons/".$s['year']);?>">Show details</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php 
  $this->endSection();?>