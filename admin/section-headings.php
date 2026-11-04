<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$sections = tt_home_sections($data);
$keys = ['hero', 'boats', 'programs', 'packages3d2n', 'packages4d3n', 'booking', 'reviews', 'videos', 'office', 'cta'];

$labels = [
    'hero' => 'Banner — text over the slide image',
    'boats' => 'Day trip packages section (#boats)',
    'programs' => '2 days 1 night packages section (#home-section-programs)',
    'packages3d2n' => '3 days 2 nights packages section (#home-section-packages3d2n)',
    'packages4d3n' => '4 days 3 nights packages section (#home-section-packages4d3n)',
    'booking' => 'Booking steps section (4 steps)',
    'reviews' => 'Customer reviews section',
    'videos' => 'TikTok / Videos section',
    'office' => 'Office / Map section',
    'cta' => 'CTA section at bottom of page',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $out = [];
    foreach ($keys as $key) {
        $out[$key] = [
            'eyebrow' => trim($_POST[$key . '_eyebrow'] ?? ''),
            'title' => trim($_POST[$key . '_title'] ?? ''),
            'lead' => trim($_POST[$key . '_lead'] ?? ''),
        ];
        if ($key === 'videos') {
            $out[$key]['tiktokHandle'] = trim($_POST['videos_tiktokHandle'] ?? '');
        }
        if ($key === 'office') {
            $out[$key]['infoTitle'] = trim($_POST['office_infoTitle'] ?? '');
            $out[$key]['infoLead'] = trim($_POST['office_infoLead'] ?? '');
        }
        if ($key === 'hero') {
            unset($out[$key]['eyebrow']);
        }
    }
    $data['homeSections'] = $out;
    if (tt_write_data($data)) {
        tt_set_flash('Section headings saved — open the home page and refresh to see the result');
    } else {
        tt_set_flash('Save failed', 'error');
    }
    header('Location: section-headings.php');
    exit;
}

tt_admin_header('Section headings', 'section-headings');
$flash = tt_flash();
$flashType = tt_flash_type();
if ($flash): ?>
  <div class="alert alert-<?= $flashType === 'error' ? 'error' : 'success' ?>"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="card">
  <h2>Home page section headings</h2>
  <p class="field-hint">Edit only the <strong>main heading</strong> of each Section on index.html (Eyebrow / H2 / description) — excluding list cards. Day trip package cards / programs / reviews are edited in the <a href="programs.php">Tour programs</a> menu (the # code on the card), <a href="boats.php">Boat types</a>, <a href="reviews.php">Reviews</a></p>
</div>

<form method="post">
  <?php foreach ($keys as $key):
    $s = $sections[$key] ?? [];
    $label = $labels[$key] ?? $key;
  ?>
  <div class="card" style="margin-bottom:16px" id="section-<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>">
    <div class="admin-section-title">
      <h2><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></h2>
      <?= tt_render_admin_section_help($key) ?>
    </div>
    <div class="grid-2">
      <?php if ($key !== 'hero'): ?>
      <div class="field">
        <label>Small text above (Eyebrow)</label>
        <input name="<?= $key ?>_eyebrow" value="<?= htmlspecialchars($s['eyebrow'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
      </div>
      <?php endif; ?>
      <div class="field" style="<?= $key === 'hero' ? 'grid-column:1/-1' : '' ?>">
        <label>Main heading (H2)</label>
        <input name="<?= $key ?>_title" value="<?= htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
      </div>
      <?php if ($key === 'office'): ?>
      <div class="field" style="grid-column:1/-1">
        <label>Heading in the office info box</label>
        <input name="office_infoTitle" value="<?= htmlspecialchars($s['infoTitle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label>Description in the office info box</label>
        <textarea name="office_infoLead" rows="2"><?= htmlspecialchars($s['infoLead'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <?php endif; ?>
      <?php if ($key !== 'office'): ?>
      <div class="field" style="grid-column:1/-1">
        <label>Description (Lead)</label>
        <textarea name="<?= $key ?>_lead" rows="2"><?= htmlspecialchars($s['lead'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <?php endif; ?>
      <?php if ($key === 'videos'): ?>
      <div class="field">
        <label>TikTok name in the link (e.g. @talaytrang)</label>
        <input name="videos_tiktokHandle" value="<?= htmlspecialchars($s['tiktokHandle'] ?? '@talaytrang', ENT_QUOTES, 'UTF-8') ?>"/>
        <p class="field-hint">The link goes to the TikTok URL from the <a href="site.php">Site info</a> menu</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save all section headings</button>
    <a class="btn btn-ghost" href="../index.html" target="_blank" rel="noopener">Open home page ↗</a>
  </div>
</form>

<?php tt_admin_footer(); ?>
