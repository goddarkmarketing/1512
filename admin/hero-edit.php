<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
tt_require_admin();

$data = tt_read_data();
$slides = $data['heroSlides'] ?? [];
$idx = isset($_GET['i']) ? (int)$_GET['i'] : -1;
$slide = ($idx >= 0 && isset($slides[$idx])) ? $slides[$idx] : null;
$isNew = $slide === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postIdx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    $src = trim($_POST['src'] ?? '');
    if ($src === '') {
        tt_set_flash('Please enter an image path');
        header('Location: hero-edit.php' . ($postIdx >= 0 ? '?i=' . $postIdx : ''));
        exit;
    }

    $item = [
        'src' => $src,
        'alt' => trim($_POST['alt'] ?? ''),
    ];

    if ($postIdx >= 0 && isset($slides[$postIdx])) {
        $slides[$postIdx] = $item;
    } else {
        $slides[] = $item;
        $postIdx = count($slides) - 1;
    }

    $data['heroSlides'] = $slides;
    tt_write_data($data);
    tt_set_flash('Slide saved');
    header('Location: hero-edit.php?i=' . $postIdx);
    exit;
}

$pageTitle = $isNew ? 'Add Hero slide' : 'Edit Hero slide';
tt_admin_header($pageTitle, 'hero');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Hero slide (home page)</h2>
    <p class="field-hint">Enter an image path within the website, e.g. <code>assets/cover%20hero/slide-01.png</code>, or use the upload button below · <a href="media.php">View image size table</a></p>

    <?php if (!empty($slide['src'])): ?>
      <img src="../<?= htmlspecialchars($slide['src'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-width:320px;border-radius:10px;margin-bottom:16px;border:1px solid var(--adm-border)"/>
    <?php endif; ?>

    <input type="hidden" name="idx" value="<?= $isNew ? -1 : $idx ?>"/>

    <div class="grid-2">
      <?php tt_render_image_url_field('Image path (src)', 'src', $slide['src'] ?? '', [
          'context' => 'hero_slide',
          'required' => true,
          'placeholder' => 'assets/cover%20hero/slide-01.png',
          'style' => 'grid-column:1/-1',
      ]); ?>
      <div class="field" style="grid-column:1/-1"><label>Image description (alt)</label><input name="alt" value="<?= htmlspecialchars($slide['alt'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Boat rental, Trang sea tours — Talay Trang"/></div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="hero.php">Back</a>
      <a class="btn btn-ghost" href="../index.html" target="_blank">View home page</a>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
