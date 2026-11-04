<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
tt_require_admin();

$data = tt_read_data();
$articles = $data['articles'] ?? [];
$slug = $_GET['slug'] ?? '';
$article = null;
$idx = -1;

foreach ($articles as $i => $a) {
    if (($a['slug'] ?? '') === $slug) {
        $article = $a;
        $idx = $i;
        break;
    }
}

$isNew = $article === null;
$markdown = $article ? tt_body_to_markdown($article['body'] ?? []) : "## Subheading\n\nFirst paragraph of the article\n\n- Item 1\n- Item 2";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSlug = trim($_POST['slug'] ?? '');
    if ($newSlug === '') {
        $newSlug = tt_slugify($_POST['title'] ?? 'article');
    }
    $body = tt_markdown_to_body($_POST['markdown'] ?? '');

    $item = [
        'slug' => $newSlug,
        'category' => trim($_POST['category'] ?? ''),
        'title' => trim($_POST['title'] ?? ''),
        'excerpt' => trim($_POST['excerpt'] ?? ''),
        'cover' => trim($_POST['cover'] ?? ''),
        'thumb' => trim($_POST['thumb'] ?? ''),
        'author' => trim($_POST['author'] ?? 'Talay Trang Team'),
        'date' => trim($_POST['date'] ?? date('j M Y')),
        'readTime' => trim($_POST['readTime'] ?? '5 min'),
        'body' => $body,
    ];

    if ($idx >= 0) {
        $articles[$idx] = $item;
    } else {
        $articles[] = $item;
    }

    $data['articles'] = $articles;
    tt_write_data($data);
    tt_set_flash('Article saved');
    header('Location: article-edit.php?slug=' . urlencode($newSlug));
    exit;
}

$title = $isNew ? 'Write new article' : 'Edit article';
tt_admin_header($title, 'articles');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="card">
  <div class="alert alert-info">
    <strong>Simple Markdown:</strong><br>
    <code>## Heading</code> · <code>&gt; Quote</code> · <code>- List item</code> · <code>![alt](url)</code> · Plain paragraph
  </div>
</div>

<form method="post">
  <div class="card">
    <h2>Article details</h2>
    <div class="grid-2">
      <div class="field"><label>Title</label><input name="title" required value="<?= htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Slug (URL)</label><input name="slug" value="<?= htmlspecialchars($article['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="five-must-see-islands-trang"/></div>
      <div class="field"><label>Category</label><input name="category" value="<?= htmlspecialchars($article['category'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Author</label><input name="author" value="<?= htmlspecialchars($article['author'] ?? 'Talay Trang Team', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Date</label><input name="date" value="<?= htmlspecialchars($article['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Reading time</label><input name="readTime" value="<?= htmlspecialchars($article['readTime'] ?? '5 min', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Excerpt</label><textarea name="excerpt"><?= htmlspecialchars($article['excerpt'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <?php tt_render_image_url_field('Cover image (cover URL)', 'cover', $article['cover'] ?? '', ['context' => 'article_cover']); ?>
      <?php tt_render_image_url_field('Thumbnail (thumb URL)', 'thumb', $article['thumb'] ?? '', ['context' => 'article_thumb']); ?>
    </div>
  </div>

  <div class="card">
    <h2>Content (Markdown)</h2>
    <div class="field">
      <textarea name="markdown" class="tall"><?= htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save article</button>
      <a class="btn btn-ghost" href="articles.php">Back</a>
      <a class="btn btn-ghost" href="media.php">Upload image</a>
      <?php if (!$isNew): ?>
        <a class="btn btn-ghost" href="../article.html?slug=<?= urlencode($article['slug'] ?? '') ?>" target="_blank">View on site</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
