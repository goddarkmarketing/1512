<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$reviews = $data['reviews'] ?? [];
$idx = isset($_GET['i']) ? (int)$_GET['i'] : -1;
$review = ($idx >= 0 && isset($reviews[$idx])) ? $reviews[$idx] : null;
$isNew = $review === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postIdx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        tt_set_flash('Please enter the customer name');
        header('Location: review-edit.php' . ($postIdx >= 0 ? '?i=' . $postIdx : ''));
        exit;
    }

    $initial = trim($_POST['initial'] ?? '');
    if ($initial === '') {
        $initial = mb_substr($name, 0, 1);
    }

    $item = [
        'name' => $name,
        'initial' => $initial,
        'trip' => trim($_POST['trip'] ?? ''),
        'from' => trim($_POST['from'] ?? ''),
        'rating' => max(1, min(5, (int)($_POST['rating'] ?? 5))),
        'text' => trim($_POST['text'] ?? ''),
    ];

    if ($postIdx >= 0 && isset($reviews[$postIdx])) {
        $reviews[$postIdx] = $item;
    } else {
        $reviews[] = $item;
        $postIdx = count($reviews) - 1;
    }

    $data['reviews'] = $reviews;
    tt_write_data($data);
    tt_set_flash('Review saved');
    header('Location: review-edit.php?i=' . $postIdx);
    exit;
}

$pageTitle = $isNew ? 'Add review' : 'Edit review';
tt_admin_header($pageTitle, 'reviews');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Review details</h2>
    <input type="hidden" name="idx" value="<?= $isNew ? -1 : $idx ?>"/>

    <div class="grid-2">
      <div class="field"><label>Customer name</label><input name="name" required value="<?= htmlspecialchars($review['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Ms. Pim Khajorn"/></div>
      <div class="field"><label>Initial (circle)</label><input name="initial" maxlength="2" value="<?= htmlspecialchars($review['initial'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Blank = use first letter of name"/></div>
      <div class="field"><label>Trip</label><input name="trip" value="<?= htmlspecialchars($review['trip'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="4-island program"/></div>
      <div class="field"><label>From province</label><input name="from" value="<?= htmlspecialchars($review['from'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Bangkok"/></div>
      <div class="field"><label>Rating (1–5)</label><input name="rating" type="number" min="1" max="5" value="<?= (int)($review['rating'] ?? 5) ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Review text</label><textarea name="text" rows="5"><?= htmlspecialchars($review['text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>

    <p class="field-hint">Reviews are shown on the home page in the customer reviews section</p>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="reviews.php">Back</a>
      <a class="btn btn-ghost" href="../index.html#home-reviews" target="_blank">View home page</a>
      <?php if (!$isNew): ?>
        <a class="btn btn-danger" href="reviews.php?delete=<?= $idx ?>" onclick="return confirm('Delete this review?')">Delete this review</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
