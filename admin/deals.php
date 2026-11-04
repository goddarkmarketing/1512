<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
tt_require_admin();

$data = tt_read_data();
$custom = $data['homeDeals']['customTrip'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['homeDeals'] = [
        'customTrip' => [
            'enabled' => !empty($_POST['enabled']),
            'href' => trim($_POST['href'] ?? 'booking.html'),
            'image' => trim($_POST['image'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'sub' => trim($_POST['sub'] ?? ''),
            'rating' => (float)($_POST['rating'] ?? 4.8),
            'reviewCount' => trim($_POST['reviewCount'] ?? ''),
            'stars' => (int)($_POST['stars'] ?? 5),
            'basePrice' => (int)($_POST['basePrice'] ?? 0),
            'badgeTL' => trim($_POST['badgeTL'] ?? ''),
            'badgeTR' => trim($_POST['badgeTR'] ?? ''),
            'badgeTRClass' => trim($_POST['badgeTRClass'] ?? 'is-info'),
        ],
    ];
    tt_write_data($data);
    tt_set_flash('Custom Trip card saved');
    header('Location: deals.php');
    exit;
}

tt_admin_header('Home cards (Deal)', 'deals');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="card">
  <p class="field-hint">Ratings / reviews for boats and programs are edited in the <a href="boats.php">Boat types</a> and <a href="programs.php">Tour programs</a> menus</p>
</div>

<form method="post" class="card">
  <h2>Custom Trip card (home page)</h2>
  <div class="field">
    <label><input type="checkbox" name="enabled" value="1" <?= !empty($custom['enabled']) ? 'checked' : '' ?>/> Show this card on the home page</label>
  </div>
  <div class="grid-2">
    <div class="field"><label>Name</label><input name="name" value="<?= htmlspecialchars($custom['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Subtitle</label><input name="sub" value="<?= htmlspecialchars($custom['sub'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <?php tt_render_image_url_field('Image URL', 'image', $custom['image'] ?? '', ['context' => 'deal']); ?>
    <div class="field"><label>Link</label><input name="href" value="<?= htmlspecialchars($custom['href'] ?? 'booking.html', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Rating (0–5)</label><input name="rating" type="number" step="0.1" min="0" max="5" value="<?= htmlspecialchars((string)($custom['rating'] ?? 5), ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Review count (text)</label><input name="reviewCount" value="<?= htmlspecialchars($custom['reviewCount'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="850 or 2.1k"/></div>
    <div class="field"><label>Stars (1–5)</label><input name="stars" type="number" min="1" max="5" value="<?= (int)($custom['stars'] ?? 5) ?>"/></div>
    <div class="field"><label>Starting price</label><input name="basePrice" type="number" value="<?= (int)($custom['basePrice'] ?? 0) ?>"/></div>
    <div class="field"><label>Left badge (badge TL)</label><input name="badgeTL" value="<?= htmlspecialchars($custom['badgeTL'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Right badge (badge TR)</label><input name="badgeTR" value="<?= htmlspecialchars($custom['badgeTR'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Right badge class</label><input name="badgeTRClass" value="<?= htmlspecialchars($custom['badgeTRClass'] ?? 'is-info', ENT_QUOTES, 'UTF-8') ?>" placeholder="is-hot or is-info"/></div>
  </div>
  <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button></div>
</form>

<?php tt_admin_footer(); ?>
