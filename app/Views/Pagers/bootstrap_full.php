<?php
$currentPage = (int) $currentPage;
$totalPages = (int) $totalPages;
$maxPagesToShow = 5;

$startPage = max($currentPage - intval($maxPagesToShow / 2), 1);
$endPage = $startPage + $maxPagesToShow - 1;
if ($endPage > $totalPages) {
    $endPage = $totalPages;
    $startPage = max($endPage - $maxPagesToShow + 1, 1);
}
?>

<div>
  <ul class="pagination">

    <!-- Šipka na začátek -->
    <?php if ($currentPage > 1): ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPageURI(1) ?>">&laquo;</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled">
        <span class="page-link">&laquo;</span>
      </li>
    <?php endif; ?>

    <!-- Čísla stránek -->
    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
      <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
        <a class="page-link" href="<?= $pager->getPageURI($i) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Šipka na konec -->
    <?php if ($currentPage < $totalPages): ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPageURI($totalPages) ?>">&raquo;</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled">
        <span class="page-link">&raquo;</span>
      </li>
    <?php endif; ?>

  </ul>
</div>