<?php
$this->extend("layout/layout");
$this->section("content"); 
?>
<ol class="breadcrumb mt-4">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Drivers</li>
</ol>
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Drivers</li>
                    </ol>
                </nav>
<h2>F1 Drivers</h2>

<?php if (session()->get('user_id')): ?>
<div style="margin-bottom: 15px;">
    <a class="btn btn-secondary btn-sm" href="<?= base_url("/drivers/create"); ?>">Add Driver</a>
    <a class="btn btn-primary btn-sm" href="<?= base_url("/drivers/trashed"); ?>"> Deleted drivers</a>
</div>
<?php endif; ?>

<div class="row">
    <?php foreach ($drivers as $d): ?>
        <div class="col-6 p-3 col-md-4">
            <a href="<?= base_url("/drivers/driver_details/" . $d['id']); ?>" class="text-decoration-none text-reset">
                <div class="card h-100 hover-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($d['first_name']) . ' ' . esc($d['last_name']) ?></h5>
                        <img class="card-img-top rounded" style="height: 200px; object-fit: cover;"
                             src="<?= base_url("images/" . esc($d['image'])); ?>"
                             onerror="this.onerror=null; this.src='/images/drivers/default.png';">
                        <p class="card-text mt-3"><strong>WDC:</strong> <?= esc($d['wdc']) ?></p>
                        <p class="card-text"><strong>Wins:</strong> <?= esc($d['win']) ?></p>
                        <p class="card-text"><strong>Points:</strong> <?= esc($d['points']) ?></p>
                        <p class="card-text"><strong>Nationality:</strong> <?= esc($d['nationality']) ?></p>
                        <p class="card-text"><strong>Date of birth:</strong> <?= esc(formatCzechDate($d['dob'])) ?></p>
                        <?php if (session()->get('user_id')): ?>
                         <div class="mt-2">
                            <a href="<?= base_url("/drivers/edit/" . $d['id']); ?>">Edit</a> |
                            <a href="<?= base_url("/drivers/delete/" . $d['id']); ?>" onclick="return confirm('Delete this driver?')">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

            
        </div>
    <?php endforeach; ?>
<?php
$maxPagesToShow = 5;

$startPage = max($currentPage - floor($maxPagesToShow / 2), 1);
$endPage = min($startPage + $maxPagesToShow - 1, $totalPages);

if ($endPage - $startPage < $maxPagesToShow - 1) {
    $startPage = max($endPage - $maxPagesToShow + 1, 1);
}
?>

<?php
$maxPagesToShow = 5;
$startPage = max($currentPage - floor($maxPagesToShow / 2), 1);
$endPage = min($startPage + $maxPagesToShow - 1, $totalPages);

if ($endPage - $startPage < $maxPagesToShow - 1) {
    $startPage = max($endPage - $maxPagesToShow + 1, 1);
}
?>

<div class="d-flex justify-content-center mt-4">
  <ul class="pagination">

    <!-- Šipka na začátek -->
    <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $pager->getPageURI(1) ?>">&laquo;</a>
    </li>

    <!-- Číslované stránky -->
    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
      <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
        <a class="page-link" href="<?= $pager->getPageURI($i) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Šipka na konec -->
    <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $pager->getPageURI($totalPages) ?>">&raquo;</a>
    </li>

  </ul>
</div>
<style>
    .hover-card:hover {
        background-color:rgb(236, 236, 236);

        cursor: pointer;
        transition: background-color 0.3s ease;
    }
</style>
<?php 
$this->endSection(); 
?>