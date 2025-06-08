<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Export</li>
</ol>
<div class="container mt-5">
    <h1 class="mb-4">Tabulky s prefixem</h1>

    <?php if (empty($tables)): ?>
        <div class="alert alert-warning">Nebyly nalezeny žádné tabulky s prefixem <code>f1_</code>.</div>
    <?php else: ?>
        <table class="table table-stripped">
            <thead class="">
    <tr>
        <th>#</th>
        <th>Název tabulky</th>
        <th>CSV</th>
        <th>PDF</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($tables as $i => $table): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><code><?= esc($table) ?></code></td>
            <td>
                <a href="<?= base_url('export/csv/' . $table) ?>" class="btn btn-sm btn-success">
                    CSV
                </a>
            </td>
            <td>
                <a href="<?= base_url('export/pdf/' . $table) ?>" class="btn btn-sm btn-primary">
                    PDF
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    <?php endif; ?>
</div>
<?php 
$this->endSection(); 
?>