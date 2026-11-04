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
    $bb['insurancePerPerson'] = (int) ($_POST['insurancePerPerson'] ?? 50);
    tt_bb_save($bb);
    tt_set_flash('Insurance saved');
    header('Location: boat-booking-insurance.php');
    exit;
}

$data = tt_read_data();
$bb = tt_bb_get($data);

tt_bb_page_start('⑤ Insurance & mandatory fees', 'insurance');
?>

<div class="card">
  <h2><span class="adm-step-badge adm-step-badge--lg">5</span> Insurance &amp; mandatory fees</h2>
  <p class="field-hint">Step on the website: <strong>Accident insurance fee</strong> — default for all boats (each boat profile can override it) · Guide/staff are edited in <a href="boat-booking-profiles.php">Boat profiles</a></p>
  <form method="post">
    <div class="grid-2">
      <div class="field">
        <label>Accident insurance (THB/person)</label>
        <input name="insurancePerPerson" type="number" min="0" value="<?= (int) ($bb['insurancePerPerson'] ?? 50) ?>"/>
        <p class="field-hint">Calculated × actual number of passengers</p>
      </div>
    </div>
    <?php tt_bb_form_actions('Save insurance'); ?>
  </form>
</div>

<?php tt_admin_footer(); ?>
