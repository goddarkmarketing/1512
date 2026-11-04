<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/boat-booking-helpers.php';
require_once __DIR__ . '/includes/boat-booking-layout.php';
tt_require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = tt_read_data();
    $bb = tt_bb_get($data);
    $bb['peopleTiers'] = tt_bb_parse_people_tiers_post($_POST['people_tiers'] ?? []);
    tt_bb_save($bb);
    tt_set_flash('Group sizes saved');
    header('Location: boat-booking-tiers.php');
    exit;
}

$data = tt_read_data();
$bb = tt_bb_get($data);
$peopleTiers = $bb['peopleTiers'] ?? [];
$tierRows = tt_bb_repeater_blank($peopleTiers, ['id' => '', 'label' => '', 'min' => 1, 'max' => 4]);

tt_bb_page_start('③ Group sizes', 'tiers');
?>

<div class="card">
  <h2><span class="adm-step-badge adm-step-badge--lg">3</span> Group sizes (long-tail boat)</h2>
  <p class="field-hint">Step on the website: <strong>Choose number of people</strong> — charter price ranges, e.g. 1–4, 5–8 people (ID: p1, p2, …)</p>
  <form method="post">
    <table class="table table-form">
      <thead><tr><th>ID</th><th>Label</th><th>Min</th><th>Max</th></tr></thead>
      <tbody>
        <?php foreach ($tierRows as $i => $t): ?>
        <tr>
          <td><input name="people_tiers[<?= $i ?>][id]" value="<?= htmlspecialchars((string) ($t['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="p1"/></td>
          <td><input name="people_tiers[<?= $i ?>][label]" value="<?= htmlspecialchars((string) ($t['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="1-4 people"/></td>
          <td><input name="people_tiers[<?= $i ?>][min]" type="number" min="1" value="<?= (int) ($t['min'] ?? 1) ?>"/></td>
          <td><input name="people_tiers[<?= $i ?>][max]" type="number" min="1" value="<?= (int) ($t['max'] ?? 4) ?>"/></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php tt_bb_form_actions('Save group sizes'); ?>
  </form>
</div>

<?php tt_admin_footer(); ?>
