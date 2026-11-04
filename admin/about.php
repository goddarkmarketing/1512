<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-sizes.php';
tt_require_admin();

$data = tt_read_data();
$about = $data['about'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paragraphs = array_values(array_filter(array_map('trim', explode("\n", $_POST['story_paragraphs'] ?? ''))));

    $trust = [];
    $trustValues = $_POST['trust_value'] ?? [];
    $trustLabels = $_POST['trust_label'] ?? [];
    foreach ($trustValues as $i => $val) {
        $val = trim($val);
        $label = trim($trustLabels[$i] ?? '');
        if ($val === '' && $label === '') continue;
        $trust[] = ['value' => $val, 'label' => $label];
    }

    $about = [
        'hero' => [
            'crumb' => trim($_POST['hero_crumb'] ?? ''),
            'title' => trim($_POST['hero_title'] ?? ''),
            'lead' => trim($_POST['hero_lead'] ?? ''),
            'heroAlt' => trim($_POST['hero_alt'] ?? ''),
        ],
        'story' => [
            'eyebrow' => trim($_POST['story_eyebrow'] ?? ''),
            'title' => trim($_POST['story_title'] ?? ''),
            'paragraphs' => $paragraphs,
            'ctaPrimary' => [
                'label' => trim($_POST['story_cta1_label'] ?? ''),
                'href' => trim($_POST['story_cta1_href'] ?? ''),
            ],
            'ctaSecondary' => [
                'label' => trim($_POST['story_cta2_label'] ?? ''),
                'href' => trim($_POST['story_cta2_href'] ?? ''),
            ],
        ],
        'whySection' => [
            'eyebrow' => trim($_POST['why_eyebrow'] ?? ''),
            'title' => trim($_POST['why_title'] ?? ''),
        ],
        'trust' => $trust,
        'office' => [
            'eyebrow' => trim($_POST['office_eyebrow'] ?? ''),
            'title' => trim($_POST['office_title'] ?? ''),
            'infoTitle' => trim($_POST['office_info_title'] ?? ''),
            'infoText' => trim($_POST['office_info_text'] ?? ''),
        ],
        'cta' => [
            'title' => trim($_POST['cta_title'] ?? ''),
            'lead' => trim($_POST['cta_lead'] ?? ''),
            'lineLabel' => trim($_POST['cta_line_label'] ?? ''),
            'bookingLabel' => trim($_POST['cta_booking_label'] ?? ''),
        ],
    ];

    $data['about'] = $about;
    tt_write_data($data);
    tt_set_flash('About page saved');
    header('Location: about.php');
    exit;
}

$hero = $about['hero'] ?? [];
$story = $about['story'] ?? [];
$whySection = $about['whySection'] ?? [];
$trust = $about['trust'] ?? [];
$office = $about['office'] ?? [];
$cta = $about['cta'] ?? [];
$ctaPrimary = $story['ctaPrimary'] ?? [];
$ctaSecondary = $story['ctaSecondary'] ?? [];

tt_admin_header('About page', 'about');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Hero</h2>
    <div class="grid-2">
      <div class="field"><label>Crumb</label><input name="hero_crumb" value="<?= htmlspecialchars($hero['crumb'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Hero image alt</label><input name="hero_alt" value="<?= htmlspecialchars($hero['heroAlt'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Main heading</label><input name="hero_title" value="<?= htmlspecialchars($hero['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Lead</label><textarea name="hero_lead"><?= htmlspecialchars($hero['lead'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>
    <p class="field-hint">Edit the Hero image in the <a href="images.php">Images (Registry)</a> menu → key <code>heroAbout</code> (<?= htmlspecialchars(tt_image_size_hint('heroAbout'), ENT_QUOTES, 'UTF-8') ?>)</p>
  </div>

  <div class="card">
    <h2>Our story</h2>
    <div class="grid-2">
      <div class="field"><label>Eyebrow</label><input name="story_eyebrow" value="<?= htmlspecialchars($story['eyebrow'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Title</label><input name="story_title" value="<?= htmlspecialchars($story['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Paragraphs (one paragraph per line)</label><textarea name="story_paragraphs" rows="5"><?= htmlspecialchars(implode("\n", $story['paragraphs'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field"><label>Primary button — text</label><input name="story_cta1_label" value="<?= htmlspecialchars($ctaPrimary['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Primary button — link</label><input name="story_cta1_href" value="<?= htmlspecialchars($ctaPrimary['href'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Secondary button — text</label><input name="story_cta2_label" value="<?= htmlspecialchars($ctaSecondary['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Secondary button — link</label><input name="story_cta2_href" value="<?= htmlspecialchars($ctaSecondary['href'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    </div>
    <p class="field-hint">The 3 images in the story grid are edited at <code>about1</code> (<?= htmlspecialchars(tt_image_size_hint('about1'), ENT_QUOTES, 'UTF-8') ?>), <code>about2</code> / <code>about3</code> (<?= htmlspecialchars(tt_image_size_hint('about2'), ENT_QUOTES, 'UTF-8') ?>) in the <a href="images.php">Images</a> menu</p>
  </div>

  <div class="card">
    <h2>Why us section (heading)</h2>
    <p class="field-hint">Edit the list of highlights in the <a href="why.php">Highlights</a> menu</p>
    <div class="grid-2">
      <div class="field"><label>Eyebrow</label><input name="why_eyebrow" value="<?= htmlspecialchars($whySection['eyebrow'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Title</label><input name="why_title" value="<?= htmlspecialchars($whySection['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    </div>
  </div>

  <div class="card">
    <h2>Trust strip (credibility numbers)</h2>
    <?php
    $trustRows = $trust ?: [['value' => '', 'label' => '']];
    foreach ($trustRows as $i => $t): ?>
      <div class="grid-2 item-card" style="margin-bottom:12px">
        <div class="field"><label>Number #<?= $i + 1 ?></label><input name="trust_value[]" value="<?= htmlspecialchars($t['value'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
        <div class="field"><label>Description</label><input name="trust_label[]" value="<?= htmlspecialchars($t['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      </div>
    <?php endforeach; ?>
    <p class="field-hint">To add a row, fill in the empty fields and save — to remove a row, clear the text in both fields</p>
  </div>

  <div class="card">
    <h2>Office / Map</h2>
    <p class="field-hint">Address, phone, LINE and map are pulled automatically from <a href="site.php">Site info</a></p>
    <div class="grid-2">
      <div class="field"><label>Eyebrow</label><input name="office_eyebrow" value="<?= htmlspecialchars($office['eyebrow'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Title</label><input name="office_title" value="<?= htmlspecialchars($office['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Info heading</label><input name="office_info_title" value="<?= htmlspecialchars($office['infoTitle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Info text</label><textarea name="office_info_text"><?= htmlspecialchars($office['infoText'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>
  </div>

  <div class="card">
    <h2>CTA at bottom of page</h2>
    <div class="grid-2">
      <div class="field" style="grid-column:1/-1"><label>Title</label><input name="cta_title" value="<?= htmlspecialchars($cta['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Lead</label><textarea name="cta_lead"><?= htmlspecialchars($cta['lead'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field"><label>LINE button</label><input name="cta_line_label" value="<?= htmlspecialchars($cta['lineLabel'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Booking button</label><input name="cta_booking_label" value="<?= htmlspecialchars($cta['bookingLabel'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    </div>
  </div>

  <div class="form-actions">
    <a class="btn btn-ghost" href="../about.html" target="_blank">View page</a>
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>

<?php tt_admin_footer(); ?>
