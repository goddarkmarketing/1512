<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/frontend-icons.php';
require_once __DIR__ . '/includes/boat-booking-helpers.php';
require_once __DIR__ . '/includes/boat-booking-layout.php';
tt_require_admin();

$iconOptions = tt_bb_icon_options();

$data = tt_read_data();
$boatId = trim($_GET['boat'] ?? $_POST['boat'] ?? '');
$boatIds = tt_bb_boat_ids($data);
if ($boatId === '' || !in_array($boatId, $boatIds, true)) {
    tt_set_flash('Boat type not found');
    header('Location: boat-booking-profiles.php');
    exit;
}

$bb = tt_bb_get($data);
$routes = $bb['routes'] ?? [];
$peopleTiers = $bb['peopleTiers'] ?? [];
$profile = $bb['profiles'][$boatId] ?? [];
$isSizeMode = ($profile['selectionMode'] ?? '') === 'size';
$sizes = $profile['sizes'] ?? [];
$charterPrices = $profile['charterPrices'] ?? ($bb['charterPrices'][$boatId] ?? []);
$profileAddons = $profile['addons'] ?? [];
$routeIds = $profile['routeIds'] ?? [];
$tierRules = $profile['tierRules'] ?? [];
$guideCounts = $profile['guideCountByTier'] ?? [];
$addonQtyBySize = $profile['addonQtyBySize'] ?? [];
$qtyDefaultAddonIds = tt_bb_qty_default_addon_ids(array_values(array_filter($profileAddons, static fn($a) => ($a['id'] ?? '') !== '')));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = tt_read_data();
    $bb = tt_bb_get($data);
    $existing = $bb['profiles'][$boatId] ?? [];
    $profile = tt_bb_merge_profile_post($existing, $_POST, $bb, $boatId);

    $bb['profiles'] = $bb['profiles'] ?? [];
    $bb['profiles'][$boatId] = $profile;

    if (($profile['selectionMode'] ?? '') !== 'size' && !empty($profile['charterPrices'])) {
        $bb['charterPrices'] = $bb['charterPrices'] ?? [];
        $bb['charterPrices'][$boatId] = $profile['charterPrices'];
    }

    $data['boatBooking'] = $bb;
    tt_write_data($data);
    tt_set_flash('Boat booking profile saved');
    header('Location: boat-booking-profile-edit.php?boat=' . urlencode($boatId));
    exit;
}

$bb = tt_bb_get($data);
$profile = $bb['profiles'][$boatId] ?? [];
$isSizeMode = ($profile['selectionMode'] ?? '') === 'size';
$sizes = $profile['sizes'] ?? [];
if (!$sizes && $isSizeMode) {
    $sizes = [['id' => '', 'label' => '', 'capacity' => 0, 'capacityLabel' => '']];
}
$sizes[] = ['id' => '', 'label' => '', 'capacity' => 0, 'capacityLabel' => ''];
$charterPrices = $profile['charterPrices'] ?? ($bb['charterPrices'][$boatId] ?? []);
$profileAddons = $profile['addons'] ?? [];
$profileAddons[] = ['id' => '', 'icon' => 'plus', 'label' => '', 'desc' => '', 'price' => 0, 'unit' => 'flat', 'priceLabel' => ''];
$routeIds = $profile['routeIds'] ?? [];
$tierRules = $profile['tierRules'] ?? [];
$guideCounts = $profile['guideCountByTier'] ?? [];
$addonQtyBySize = $profile['addonQtyBySize'] ?? [];
$qtyDefaultAddonIds = tt_bb_qty_default_addon_ids(array_values(array_filter($profileAddons, static fn($a) => ($a['id'] ?? '') !== '')));

$tierCols = $isSizeMode ? $sizes : $peopleTiers;
$tierCols = array_values(array_filter($tierCols, static fn($t) => ($t['id'] ?? '') !== ''));
$routeList = $routeIds
    ? array_values(array_filter($routes, static fn($r) => in_array($r['id'] ?? '', $routeIds, true)))
    : $routes;
if (!$routeList) {
    $routeList = $routes;
}

$boatName = $boatId;
foreach ($data['boats'] ?? [] as $b) {
    if (($b['id'] ?? '') === $boatId) {
        $boatName = (string) ($b['name'] ?? $boatId);
        break;
    }
}

$pageTitle = 'Booking profile: ' . $boatName;
tt_admin_header($pageTitle, 'boat-booking-profiles');
tt_bb_render_flash();
?>

<div class="card admin-preview-bar adm-bb-profile-bar">
  <a class="btn btn-ghost btn-sm" href="boat-booking-profiles.php">← Profile list</a>
  <a class="btn btn-ghost btn-sm" href="../boat-book.html?boat=<?= urlencode($boatId) ?>" target="_blank" rel="noopener">Test booking <?= htmlspecialchars($boatName, ENT_QUOTES, 'UTF-8') ?> ↗</a>
</div>

<form method="post" class="admin-package-form">
  <input type="hidden" name="boat" value="<?= htmlspecialchars($boatId, ENT_QUOTES, 'UTF-8') ?>"/>

  <div class="card">
    <h2>Selection mode &amp; text</h2>
    <div class="grid-2">
      <div class="field">
        <label>Selection mode</label>
        <select name="selectionMode">
          <option value="people"<?= !$isSizeMode ? ' selected' : '' ?>>Group size (people)</option>
          <option value="size"<?= $isSizeMode ? ' selected' : '' ?>>Boat size (size)</option>
        </select>
      </div>
      <div class="field">
        <label>Accident insurance (THB/person)</label>
        <input name="insurancePerPerson" type="number" min="0" value="<?= (int) ($profile['insurancePerPerson'] ?? $bb['insurancePerPerson'] ?? 50) ?>"/>
      </div>
      <div class="field"><label>Step label (progress)</label><input name="tierProgressLabel" value="<?= htmlspecialchars((string) ($profile['tierProgressLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Group size / boat size"/></div>
      <div class="field"><label>Selection step title</label><input name="tierStepTitle" value="<?= htmlspecialchars((string) ($profile['tierStepTitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1"><label>Selection step description</label><textarea name="tierStepDesc" rows="2"><?= htmlspecialchars((string) ($profile['tierStepDesc'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field"><label>Boat price label</label><input name="charterLabel" value="<?= htmlspecialchars((string) ($profile['charterLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Boat price summary prefix</label><input name="charterSummaryPrefix" value="<?= htmlspecialchars((string) ($profile['charterSummaryPrefix'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Included-in-price box title</label><input name="includedBoxTitle" value="<?= htmlspecialchars((string) ($profile['includedBoxTitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field" style="grid-column:1/-1">
        <label><input type="checkbox" name="askPax" value="1"<?= ($profile['askPax'] ?? true) ? ' checked' : '' ?>/> Ask for the actual number of passengers (insurance calculation / per person)</label>
      </div>
      <div class="field" style="grid-column:1/-1"><label>Summary footer note</label><textarea name="footerNote" rows="2"><?= htmlspecialchars((string) ($profile['footerNote'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field" style="grid-column:1/-1"><label>Items included in boat price (one per line)</label><textarea name="includedItems" rows="4" placeholder="Snorkeling gear&#10;Drinking water"><?= htmlspecialchars(implode("\n", $profile['includedItems'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>
  </div>

  <div class="card">
    <h2>Advanced behavior</h2>
    <p class="field-hint">Configure per booking scenario — long-tail boat (multiple boats), Speedboat / large boat (LINE quote, auto size selection)</p>
    <div class="grid-2">
      <div class="field" style="grid-column:1/-1">
        <label><input type="checkbox" name="multiBoat" value="1"<?= !empty($profile['multiBoat']) ? ' checked' : '' ?>/> Multiple boats (long-tail boat) — calculate number of boats from number of people</label>
      </div>
      <div class="field">
        <label>Capacity per boat (people)</label>
        <input name="capacityPerBoat" type="number" min="1" value="<?= (int) ($profile['capacityPerBoat'] ?? 0) ?>" placeholder="15"/>
        <p class="field-hint">Used when multiple boats is enabled, e.g. 30 people ÷ 15 = 2 boats</p>
      </div>
      <div class="field">
        <label><input type="checkbox" name="splitPax" value="1"<?= !empty($profile['splitPax']) ? ' checked' : '' ?>/> Separate adults / children (park fee priced separately)</label>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label><input type="checkbox" name="itemizedQuote" value="1"<?= !empty($profile['itemizedQuote']) ? ' checked' : '' ?>/> Line-by-line LINE quote (Speedboat / large boat)</label>
      </div>
      <?php if ($isSizeMode): ?>
      <div class="field">
        <label><input type="checkbox" name="autoSizeFromPax" value="1"<?= !empty($profile['autoSizeFromPax']) ? ' checked' : '' ?>/> Auto-select boat size from number of people</label>
      </div>
      <div class="field">
        <label>Default size (optional)</label>
        <select name="defaultSizeId">
          <option value="">— Automatic —</option>
          <?php foreach (array_slice($sizes, 0, -1) as $s):
            $sid = (string) ($s['id'] ?? '');
            if ($sid === '') continue;
          ?>
          <option value="<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>"<?= ($profile['defaultSizeId'] ?? '') === $sid ? ' selected' : '' ?>><?= htmlspecialchars((string) ($s['label'] ?? $sid), ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>LINE quote text</h2>
    <p class="field-hint">Used when “Line-by-line LINE quote” is enabled — can be left blank, defaults are used</p>
    <div class="grid-2">
      <div class="field">
        <label>Title prefix</label>
        <input name="quoteTitlePrefix" value="<?= htmlspecialchars((string) ($profile['quoteTitlePrefix'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Tour boat → Book 120-seat tour boat"/>
      </div>
      <div class="field">
        <label>Boat name in quote</label>
        <input name="quoteBoatName" value="<?= htmlspecialchars((string) ($profile['quoteBoatName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Speedboat → Book 45-seat Speedboat"/>
      </div>
      <div class="field">
        <label>Charter fee line name</label>
        <input name="quoteCharterName" value="<?= htmlspecialchars((string) ($profile['quoteCharterName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Boat"/>
      </div>
      <div class="field">
        <label>Insurance line label</label>
        <input name="insuranceQuoteLabel" value="<?= htmlspecialchars((string) ($profile['insuranceQuoteLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Insurance"/>
      </div>
    </div>
  </div>

  <div class="card">
    <h2>Mandatory fees (guide / staff)</h2>
    <div class="grid-2">
      <div class="field"><label>Guide fee (THB/person)</label><input name="guideFee" type="number" min="0" value="<?= (int) ($profile['guideFee'] ?? 0) ?>"/></div>
      <div class="field"><label>Safety staff (THB/person)</label><input name="safetyStaffRate" type="number" min="0" value="<?= (int) ($profile['safetyStaffRate'] ?? 0) ?>"/></div>
      <div class="field"><label>Staff ratio (customers per 1 staff)</label><input name="safetyStaffRatio" type="number" min="1" value="<?= (int) ($profile['safetyStaffRatio'] ?? 20) ?>"/></div>
    </div>
    <?php if ($isSizeMode && $sizes): ?>
    <p class="field-hint" style="margin-top:12px">Number of guides by boat size (blank = 1 person)</p>
    <table class="table table-form">
      <thead><tr><th>Size (ID)</th><th>Number of guides</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($sizes, 0, -1) as $s):
          $sid = (string) ($s['id'] ?? '');
          if ($sid === '') continue;
        ?>
        <tr>
          <td><?= htmlspecialchars((string) ($s['label'] ?? $sid), ENT_QUOTES, 'UTF-8') ?> <small>(<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>)</small></td>
          <td><input name="guideCountByTier[<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>]" type="number" min="1" value="<?= (int) ($guideCounts[$sid] ?? 1) ?>" style="width:100px"/></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <?php if ($isSizeMode): ?>
  <div class="card">
    <h2>Boat sizes</h2>
    <table class="table table-form">
      <thead><tr><th>ID</th><th>Name</th><th>Capacity (number)</th><th>Capacity text</th></tr></thead>
      <tbody>
        <?php foreach ($sizes as $i => $s): ?>
        <tr>
          <td><input name="sizes[<?= $i ?>][id]" value="<?= htmlspecialchars((string) ($s['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="s1 / t1"/></td>
          <td><input name="sizes[<?= $i ?>][label]" value="<?= htmlspecialchars((string) ($s['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/></td>
          <td><input name="sizes[<?= $i ?>][capacity]" type="number" min="1" value="<?= (int) ($s['capacity'] ?? 0) ?>"/></td>
          <td><input name="sizes[<?= $i ?>][capacityLabel]" value="<?= htmlspecialchars((string) ($s['capacityLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Seats 30"/></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($qtyDefaultAddonIds): ?>
  <div class="card">
    <h2>Default add-on quantities (by boat size)</h2>
    <p class="field-hint">Set the default quantity when a size is selected — e.g. 45 seats → 2 guides · blank = no default</p>
    <div class="table-wrap" style="overflow-x:auto">
      <table class="table table-form">
        <thead>
          <tr>
            <th>Boat size</th>
            <?php foreach ($qtyDefaultAddonIds as $aid):
              $addonLabel = $aid;
              foreach ($profileAddons as $a) {
                  if (($a['id'] ?? '') === $aid) {
                      $addonLabel = (string) ($a['label'] ?? $aid);
                      break;
                  }
              }
            ?>
            <th><?= htmlspecialchars($addonLabel, ENT_QUOTES, 'UTF-8') ?><br><small><?= htmlspecialchars($aid, ENT_QUOTES, 'UTF-8') ?></small></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach (array_slice($sizes, 0, -1) as $s):
            $sid = (string) ($s['id'] ?? '');
            if ($sid === '') continue;
          ?>
          <tr>
            <td><strong><?= htmlspecialchars((string) ($s['label'] ?? $sid), ENT_QUOTES, 'UTF-8') ?></strong> <small>(<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>)</small></td>
            <?php foreach ($qtyDefaultAddonIds as $aid):
              $val = $addonQtyBySize[$sid][$aid] ?? '';
            ?>
            <td><input name="addonQtyBySize[<?= htmlspecialchars($sid, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($aid, ENT_QUOTES, 'UTF-8') ?>]" type="number" min="0" value="<?= $val !== '' ? (int) $val : '' ?>" placeholder="—" style="width:80px"/></td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <div class="card">
    <h2>Available routes</h2>
    <p class="field-hint">None selected = show all routes · select only for long-tail boats that have 5 routes</p>
    <div class="grid-2">
      <?php foreach ($routes as $r):
        $rid = (string) ($r['id'] ?? '');
      ?>
      <label class="field" style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="routeIds[]" value="<?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?>"<?= in_array($rid, $routeIds, true) ? ' checked' : '' ?>/>
        <?= htmlspecialchars((string) ($r['name'] ?? $rid), ENT_QUOTES, 'UTF-8') ?>
      </label>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (!$isSizeMode && $routes): ?>
  <div class="card">
    <h2>Route-specific group size rules</h2>
    <p class="field-hint">Hide a tier (e.g. p4) or cap the maximum in a tier (e.g. 9–12 max 10 people)</p>
    <table class="table table-form">
      <thead><tr><th>Route</th><th>Hide tiers (comma-separated)</th><th>Max limit (tierId=count)</th></tr></thead>
      <tbody>
        <?php foreach ($routes as $r):
          $rid = (string) ($r['id'] ?? '');
          $rule = $tierRules[$rid] ?? [];
          $hide = implode(', ', $rule['hideTiers'] ?? []);
          $caps = $rule['tierCaps'] ?? [];
        ?>
        <tr>
          <td><?= htmlspecialchars((string) ($r['name'] ?? $rid), ENT_QUOTES, 'UTF-8') ?></td>
          <td><input name="tierRules[<?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?>][hideTiers]" value="<?= htmlspecialchars($hide, ENT_QUOTES, 'UTF-8') ?>" placeholder="p4"/></td>
          <td>
            <?php foreach ($peopleTiers as $t):
              $tid = (string) ($t['id'] ?? '');
            ?>
            <span style="display:inline-flex;align-items:center;gap:4px;margin-right:8px">
              <small><?= htmlspecialchars($tid, ENT_QUOTES, 'UTF-8') ?></small>
              <input name="tierRules[<?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?>][tierCaps][<?= htmlspecialchars($tid, ENT_QUOTES, 'UTF-8') ?>]" type="number" min="0" value="<?= isset($caps[$tid]) ? (int) $caps[$tid] : '' ?>" placeholder="—" style="width:70px"/>
            </span>
            <?php endforeach; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <div class="card">
    <h2>Charter prices (Route × <?= $isSizeMode ? 'Boat size' : 'Group size' ?>)</h2>
    <?php if (!$tierCols): ?>
      <p class="field-hint">Please define <?= $isSizeMode ? 'boat sizes' : 'group sizes' ?> first</p>
    <?php else: ?>
    <div class="table-wrap" style="overflow-x:auto">
      <table class="table table-form adm-charter-matrix">
        <thead>
          <tr>
            <th>Route</th>
            <?php foreach ($tierCols as $t): ?>
              <th><?= htmlspecialchars((string) ($t['label'] ?? $t['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($routeList as $r):
            $rid = (string) ($r['id'] ?? '');
          ?>
          <tr>
            <td><strong><?= htmlspecialchars((string) ($r['name'] ?? $rid), ENT_QUOTES, 'UTF-8') ?></strong></td>
            <?php foreach ($tierCols as $t):
              $tid = (string) ($t['id'] ?? '');
              if ($tid === '') continue;
              $val = $charterPrices[$rid][$tid] ?? '';
            ?>
            <td><input name="charter_prices[<?= htmlspecialchars($rid, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($tid, ENT_QUOTES, 'UTF-8') ?>]" type="number" min="0" value="<?= $val !== '' ? (int) $val : '' ?>" placeholder="—"/></td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>Add-ons (this boat only)</h2>
    <p class="field-hint">Leave all blank = use the general add-ons from the charter boat booking page</p>
    <div class="adm-repeater">
      <?php foreach ($profileAddons as $i => $a): ?>
      <article class="adm-repeater-card">
        <div class="adm-repeater-grid">
          <div class="field">
            <label>ID</label>
            <input name="addons[<?= $i ?>][id]" value="<?= htmlspecialchars((string) ($a['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="menu-park"/>
          </div>
          <div class="field">
            <label>Icon</label>
            <?php tt_icon_select("addons[{$i}][icon]", $iconOptions, (string) ($a['icon'] ?? 'plus')); ?>
          </div>
          <div class="field">
            <label>Price (THB)</label>
            <input name="addons[<?= $i ?>][price]" type="number" min="0" value="<?= (int) ($a['price'] ?? 0) ?>"/>
          </div>
          <div class="field">
            <label>Unit</label>
            <?php tt_bb_render_addon_unit_select("addons[{$i}][unit]", (string) ($a['unit'] ?? 'flat')); ?>
          </div>
          <div class="field span-2">
            <label>Display name</label>
            <input name="addons[<?= $i ?>][label]" value="<?= htmlspecialchars((string) ($a['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>
          </div>
          <div class="field span-2">
            <label>Price text</label>
            <input name="addons[<?= $i ?>][priceLabel]" value="<?= htmlspecialchars((string) ($a['priceLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="40 THB/person"/>
          </div>
          <div class="field">
            <label>Quantity label (perQty/perVan)</label>
            <input name="addons[<?= $i ?>][qtyLabel]" value="<?= htmlspecialchars((string) ($a['qtyLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="person / set / van"/>
          </div>
          <div class="field">
            <label>Adult price (adult/child split)</label>
            <input name="addons[<?= $i ?>][adultPrice]" type="number" min="0" value="<?= isset($a['adultPrice']) ? (int) $a['adultPrice'] : '' ?>" placeholder="—"/>
          </div>
          <div class="field">
            <label>Child price (adult/child split)</label>
            <input name="addons[<?= $i ?>][childPrice]" type="number" min="0" value="<?= isset($a['childPrice']) ? (int) $a['childPrice'] : '' ?>" placeholder="—"/>
          </div>
          <div class="field">
            <label>Adult label</label>
            <input name="addons[<?= $i ?>][adultLabel]" value="<?= htmlspecialchars((string) ($a['adultLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Adult park fee"/>
          </div>
          <div class="field">
            <label>Child label</label>
            <input name="addons[<?= $i ?>][childLabel]" value="<?= htmlspecialchars((string) ($a['childLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Child park fee"/>
          </div>
          <div class="field span-full">
            <label>Description</label>
            <input name="addons[<?= $i ?>][desc]" value="<?= htmlspecialchars((string) ($a['desc'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save profile</button>
    <a class="btn btn-ghost" href="boat-booking-profiles.php">Back</a>
    <a class="btn btn-ghost" href="boat-edit.php?id=<?= urlencode($boatId) ?>">Edit boat card (boats page)</a>
  </div>
</form>

<?php tt_admin_footer(); ?>
