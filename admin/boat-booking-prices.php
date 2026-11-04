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
    $bb['charterPrices'] = $bb['charterPrices'] ?? [];
    $bb['charterPrices']['longtail'] = tt_bb_parse_charter_matrix_post($_POST['charter_longtail'] ?? []);
    tt_bb_save($bb);
    tt_set_flash('Long-tail boat prices saved');
    header('Location: boat-booking-prices.php');
    exit;
}

$data = tt_read_data();
$bb = tt_bb_get($data);
$routes = $bb['routes'] ?? [];
$peopleTiers = $bb['peopleTiers'] ?? [];
$charterLongtail = $bb['charterPrices']['longtail'] ?? [];

tt_bb_page_start('④ Long-tail boat charter prices', 'prices');
?>

<div class="card">
  <h2><span class="adm-step-badge adm-step-badge--lg">4</span> Long-tail boat charter prices</h2>
  <p class="field-hint">Shown in the passenger count step — table of <strong>Route × Group size</strong> · Speedboat / tour boat prices are edited in <a href="boat-booking-profiles.php">Boat profiles</a></p>
  <?php if (!$routes || !$peopleTiers): ?>
    <p class="field-hint">Please set up <a href="boat-booking-routes.php">Routes</a> and <a href="boat-booking-tiers.php">Group sizes</a> first</p>
  <?php else: ?>
  <form method="post">
    <div class="table-wrap">
      <table class="table table-form adm-charter-matrix">
        <thead>
          <tr>
            <th>Route</th>
            <?php foreach ($peopleTiers as $t): ?>
              <th><?= htmlspecialchars((string) ($t['label'] ?? $t['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($routes as $r):
            $rid = (string) ($r['id'] ?? '');
          ?>
          <tr>
            <td><strong><?= htmlspecialchars((string) ($r['name'] ?? $rid), ENT_QUOTES, 'UTF-8') ?></strong><br><small><?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?></small></td>
            <?php foreach ($peopleTiers as $t):
              $tid = (string) ($t['id'] ?? '');
              $val = $charterLongtail[$rid][$tid] ?? '';
            ?>
            <td><input name="charter_longtail[<?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($tid, ENT_QUOTES, 'UTF-8') ?>]" type="number" min="0" value="<?= $val !== '' ? (int) $val : '' ?>" placeholder="—"/></td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php tt_bb_form_actions('Save long-tail boat prices'); ?>
  </form>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
