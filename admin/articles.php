<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$articles = $data['articles'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $slug = $_GET['delete'];
    $articles = array_values(array_filter($articles, fn($a) => ($a['slug'] ?? '') !== $slug));
    $data['articles'] = $articles;
    tt_write_data($data);
    tt_set_flash('Article deleted');
    header('Location: articles.php');
    exit;
}

tt_admin_header('Articles', 'articles');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="article-edit.php">+ Write new article</a>
</div>

<div class="card">
  <table class="table">
    <thead>
      <tr><th>Title</th><th>Category</th><th>Date</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($articles as $a): ?>
        <tr>
          <td><strong><?= htmlspecialchars($a['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong><br><small class="field-hint"><?= htmlspecialchars($a['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?></small></td>
          <td><?= htmlspecialchars($a['category'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($a['date'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="article-edit.php?slug=<?= urlencode($a['slug'] ?? '') ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="articles.php?delete=<?= urlencode($a['slug'] ?? '') ?>" onclick="return confirm('Delete this article?')">Delete</a>
              <a class="btn btn-ghost btn-sm" href="../article.html?slug=<?= urlencode($a['slug'] ?? '') ?>" target="_blank">View</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php tt_admin_footer(); ?>
