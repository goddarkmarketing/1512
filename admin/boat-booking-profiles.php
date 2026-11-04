<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/boat-booking-helpers.php';
require_once __DIR__ . '/includes/boat-booking-layout.php';
tt_require_admin();

$data = tt_read_data();
$bb = tt_bb_get($data);
$profiles = $bb['profiles'] ?? [];
$boatIds = tt_bb_boat_ids($data);

tt_bb_page_start('① Boat profiles', 'profiles');
?>

<div class="card">
  <h2><span class="adm-step-badge adm-step-badge--lg">1</span> Boat profiles</h2>
  <p class="field-hint">Step on the website: <strong>Choose boat type</strong> — edit boat sizes, charter prices, LINE quotes, multiple boats / adult-child split, and boat-specific add-ons</p>
  <table class="table">
    <thead><tr><th>Boat</th><th>Mode on website</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($boatIds as $bid):
        $p = $profiles[$bid] ?? [];
        $mode = ($p['selectionMode'] ?? '') === 'size' ? 'Choose boat size' : ($bid === 'longtail' ? 'Choose group size + actual count' : 'Choose group size');
      ?>
      <tr>
        <td><strong><?= htmlspecialchars($bid, ENT_QUOTES, 'UTF-8') ?></strong></td>
        <td><?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?></td>
        <td class="table-actions">
          <a class="btn btn-primary btn-sm" href="boat-booking-profile-edit.php?boat=<?= urlencode($bid) ?>">Edit booking profile</a>
          <a class="btn btn-ghost btn-sm" href="boat-edit.php?id=<?= urlencode($bid) ?>">Boat card</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="form-actions" style="border:0;padding-top:16px">
    <a class="btn btn-primary" href="boat-booking-routes.php">Next: Routes →</a>
  </div>
</div>

<?php tt_admin_footer(); ?>
