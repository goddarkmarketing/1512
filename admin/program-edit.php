<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
require_once __DIR__ . '/includes/package-collections.php';
tt_require_admin();

$collections = tt_package_collections();
$collection = tt_package_collection_key($_GET['collection'] ?? $_POST['collection'] ?? 'programs');
$collectionMeta = $collections[$collection];

$data = tt_read_data();
$id = trim($_GET['id'] ?? $_POST['id'] ?? '');
$found = tt_package_find($collection, $id, $data);
$program = $found['item'];
$idx = $found['index'];
$isNew = $id === '' || $program === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection = tt_package_collection_key($_POST['collection'] ?? 'programs');
    $collectionMeta = $collections[$collection];
    $data = tt_read_data();
    $oldId = trim($_POST['old_id'] ?? '');
    $found = tt_package_find($collection, $oldId, $data);
    $idx = $found['index'];
    $existingItem = is_array($found['item'] ?? null) ? $found['item'] : [];

    $newId = trim($_POST['id'] ?? '');
    if ($newId === '') {
        $newId = tt_slugify($_POST['name'] ?? 'program');
    }
    $stops = tt_parse_lines($_POST['stops'] ?? '');
    $boats = array_values(array_filter(array_map('trim', explode(',', $_POST['boats'] ?? ''))));
    $gallery = tt_parse_gallery_post($_POST['gallery'] ?? []);
    $itinerary = tt_parse_itinerary($_POST['itinerary'] ?? '');
    $inclusions = tt_parse_lines($_POST['inclusions'] ?? '');
    $priceNotes = tt_parse_lines($_POST['priceNotes'] ?? '');
    $hotels = tt_parse_hotels($_POST['hotels'] ?? '');
    $childPriceRaw = trim($_POST['childPrice'] ?? '');
    $inclusionsByDay = tt_parse_inclusions_by_day($_POST['inclusionsByDay'] ?? '');

    $item = [
        'id' => $newId,
        'name' => trim($_POST['name'] ?? ''),
        'image' => trim($_POST['image'] ?? ''),
        'route' => trim($_POST['route'] ?? ''),
        'stops' => $stops,
        'duration' => trim($_POST['duration'] ?? ''),
        'basePrice' => (int)($_POST['basePrice'] ?? 0),
        'boats' => $boats,
        'desc' => trim($_POST['desc'] ?? ''),
        'ribbon' => trim($_POST['ribbon'] ?? '') ?: null,
        'rating' => (float)($_POST['rating'] ?? 4.8),
        'reviewCount' => trim($_POST['reviewCount'] ?? ''),
        'oldPrice' => (int)($_POST['oldPrice'] ?? 0),
        'stars' => (int)($_POST['stars'] ?? 5),
        'packageCode' => trim($_POST['packageCode'] ?? ''),
        'gallery' => $gallery,
        'galleryCaption' => trim($_POST['galleryCaption'] ?? ''),
        'licenseNo' => trim($_POST['licenseNo'] ?? ''),
        'itinerary' => $itinerary,
        'season' => trim($_POST['season'] ?? ''),
        'inclusions' => $inclusions,
        'hotels' => $hotels,
        'childPrice' => $childPriceRaw === '' ? null : (int)$childPriceRaw,
        'priceMeetingNote' => trim($_POST['priceMeetingNote'] ?? ''),
        'pickupSurcharge' => trim($_POST['pickupSurcharge'] ?? '') === '' ? null : (int)($_POST['pickupSurcharge'] ?? 0),
        'priceNotes' => $priceNotes,
        'warning' => trim($_POST['warning'] ?? ''),
    ];
    if ($item['ribbon'] === null) {
        unset($item['ribbon']);
    }
    foreach (['gallery', 'galleryCaption', 'licenseNo', 'itinerary', 'season', 'inclusions', 'hotels', 'priceMeetingNote', 'priceNotes', 'warning', 'boats', 'stops'] as $k) {
        if ($item[$k] === '' || $item[$k] === [] || $item[$k] === null) {
            unset($item[$k]);
        }
    }
    if ($item['childPrice'] === null) {
        unset($item['childPrice']);
    }
    if ($item['pickupSurcharge'] === null) {
        unset($item['pickupSurcharge']);
    }

    foreach ($existingItem as $k => $v) {
        if (!array_key_exists($k, $item)) {
            $item[$k] = $v;
        }
    }
    if ($collection === 'packages4d3n' || trim($_POST['inclusionsByDay'] ?? '') !== '' || !empty($existingItem['inclusionsByDay'])) {
        if ($inclusionsByDay) {
            $item['inclusionsByDay'] = $inclusionsByDay;
        } else {
            unset($item['inclusionsByDay']);
        }
    }

    $list = $data[$collection] ?? [];
    if ($idx >= 0) {
        if ($newId !== $oldId) {
            foreach ($list as $j => $p) {
                if ($j !== $idx && ($p['id'] ?? '') === $newId) {
                    tt_set_flash('This ID already exists — use a different ID');
                    header('Location: program-edit.php?collection=' . urlencode($collection) . '&id=' . urlencode($oldId));
                    exit;
                }
            }
        }
        $list[$idx] = $item;
    } else {
        foreach ($list as $p) {
            if (($p['id'] ?? '') === $newId) {
                tt_set_flash('This ID already exists — use a different ID');
                header('Location: program-edit.php?collection=' . urlencode($collection) . '&id=' . urlencode($newId));
                exit;
            }
        }
        $list[] = $item;
    }

    $data[$collection] = array_values($list);
    tt_write_data($data);
    tt_set_flash('Package saved');
    header('Location: program-edit.php?collection=' . urlencode($collection) . '&id=' . urlencode($newId));
    exit;
}

$gallerySlots = tt_gallery_slots_for_form($program['gallery'] ?? []);
$itinLines = array_map(function ($row) {
    $time = $row['time'] ?? '';
    $title = $row['title'] ?? '';
    $text = $row['text'] ?? $row['desc'] ?? '';
    if ($title !== '') {
        return $time . '|' . $title . '|' . $text;
    }
    return $time . '|' . $text;
}, $program['itinerary'] ?? []);
$hotelsText = tt_hotels_to_text($program['hotels'] ?? []);
$inclusionsByDayText = tt_inclusions_by_day_to_text($program['inclusionsByDay'] ?? []);
$showInclusionsByDay = $collection === 'packages4d3n' || !empty($program['inclusionsByDay']);

$pageTitle = $isNew ? 'Add package' : 'Edit detail page';
tt_admin_header($pageTitle, 'programs');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<?php if (!$isNew): ?>
<div class="card admin-preview-bar">
  <div>
    <strong>Edit page:</strong>
    <a href="../program.html?id=<?= urlencode($program['id'] ?? '') ?>" target="_blank" rel="noopener">program.html?id=<?= htmlspecialchars($program['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></a>
  </div>
  <a class="btn btn-ghost btn-sm" href="../program.html?id=<?= urlencode($program['id'] ?? '') ?>" target="_blank" rel="noopener">View detail page ↗</a>
</div>
<?php endif; ?>

<form method="post" class="admin-package-form">
  <input type="hidden" name="collection" value="<?= htmlspecialchars($collection, ENT_QUOTES, 'UTF-8') ?>"/>
  <input type="hidden" name="old_id" value="<?= htmlspecialchars($program['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>

  <div class="card">
    <h2>Package type</h2>
    <div class="field">
      <label for="collection-select">Category</label>
      <select id="collection-select" class="input" onchange="location.href='program-edit.php?collection='+encodeURIComponent(this.value)+<?= $isNew ? "''" : "'&id='+encodeURIComponent('" . htmlspecialchars($program['id'] ?? '', ENT_QUOTES, 'UTF-8') . "')" ?>">
        <?php foreach ($collections as $key => $meta): ?>
          <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $key === $collection ? ' selected' : '' ?>><?= htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <p class="field-hint">Packages in <?= htmlspecialchars($collectionMeta['label'], ENT_QUOTES, 'UTF-8') ?> are shown on the home page and the programs.html page</p>
    </div>
  </div>

  <div class="card">
    <h2>Card cover image</h2>
    <p class="field-hint section-lead">Main image on the package card (home page · programs.html) — separate from the 6 gallery images on the detail page</p>
    <?php tt_render_card_image_field('image', $program['image'] ?? '', ['context' => 'program', 'label' => 'Card cover image']); ?>
  </div>

  <div class="card">
    <h2>Card details</h2>
    <p class="field-hint section-lead">Name, price and details shown on the card</p>
    <div class="grid-2">
      <div class="field"><label>Package name</label><input name="name" required value="<?= htmlspecialchars($program['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>ID (slug)</label><input name="id" value="<?= htmlspecialchars($program['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="dl001, p1-001"/></div>
      <div class="field"><label>Package code</label><input name="packageCode" value="<?= htmlspecialchars((string)($program['packageCode'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="DL001, D-002"/><p class="field-hint">Shown on the card and the meta bar of the detail page</p></div>
      <div class="field"><label>Badge (ribbon)</label><input name="ribbon" value="<?= htmlspecialchars($program['ribbon'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="650 ฿ or Popular"/></div>
      <div class="field"><label>Route</label><input name="route" value="<?= htmlspecialchars($program['route'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Duration</label><input name="duration" value="<?= htmlspecialchars($program['duration'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="About 8 hours"/></div>
      <div class="field"><label>Adult price (THB)</label><input name="basePrice" type="number" value="<?= (int)($program['basePrice'] ?? 0) ?>"/></div>
      <div class="field"><label>Supported boats (comma-separated)</label><input name="boats" value="<?= htmlspecialchars(implode(', ', $program['boats'] ?? []), ENT_QUOTES, 'UTF-8') ?>" placeholder="longtail, bigboat, speedboat"/></div>
      <div class="field"><label>Rating (0–5)</label><input name="rating" type="number" step="0.1" min="0" max="5" value="<?= htmlspecialchars((string)($program['rating'] ?? 4.8), ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Review count</label><input name="reviewCount" value="<?= htmlspecialchars($program['reviewCount'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Stars (1–5)</label><input name="stars" type="number" min="1" max="5" value="<?= (int)($program['stars'] ?? 5) ?>"/></div>
      <div class="field"><label>Original price (strikethrough)</label><input name="oldPrice" type="number" value="<?= (int)($program['oldPrice'] ?? 0) ?>" placeholder="0 = hide"/></div>
      <div class="field" style="grid-column:1/-1"><label>Stops (one per line)</label><textarea name="stops" rows="3"><?= htmlspecialchars(implode("\n", $program['stops'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field" style="grid-column:1/-1"><label>Short description</label><textarea name="desc" rows="3"><?= htmlspecialchars($program['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>
  </div>

  <div class="card">
    <h2>6-image gallery — top of the detail page</h2>
    <p class="field-hint section-lead">Arranged as on program.html: 3 images in the top row · center caption bar · 3 images in the bottom row · any empty slot falls back to the card cover image</p>
    <div class="field" style="grid-column:1/-1">
      <label>Gallery images (6 slots)</label>
      <?php tt_render_gallery_grid($gallerySlots, ['name' => 'gallery', 'context' => 'program']); ?>
    </div>
    <div class="field" style="margin-top:16px">
      <label>Caption bar over the image center (galleryCaption)</label>
      <input name="galleryCaption" value="<?= htmlspecialchars($program['galleryCaption'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Emerald Cave, Koh Kradan, Koh Waen, Koh Cheuk"/>
    </div>
    <div class="field">
      <label>Tour license</label>
      <input name="licenseNo" value="<?= htmlspecialchars($program['licenseNo'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="43/00540"/>
    </div>
  </div>

  <div class="card">
    <h2>Tour program — left column</h2>
    <div class="field" style="grid-column:1/-1">
      <label>Itinerary (one item per line — format: time|title|details)</label>
      <textarea name="itinerary" rows="10" placeholder="07:30|Pak Meng Pier|Customers meet at the pier&#10;09:30|Emerald Cave|Board the boat to Koh Mook"><?= htmlspecialchars(implode("\n", $itinLines), ENT_QUOTES, 'UTF-8') ?></textarea>
      <p class="field-hint">If left blank, it is generated automatically from the stops · use 2 parts (time|text) or 3 parts (time|title|details)</p>
    </div>
  </div>

  <div class="card">
    <h2>Purchase card &amp; pricing — right column</h2>
    <div class="grid-2">
      <div class="field"><label>Child price (THB)</label><input name="childPrice" type="number" value="<?= htmlspecialchars((string)($program['childPrice'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Blank = calculated as ~74% of adult"/></div>
      <div class="field"><label>In-town pickup fee (THB/person)</label><input name="pickupSurcharge" type="number" value="<?= htmlspecialchars((string)($program['pickupSurcharge'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="200"/></div>
      <div class="field" style="grid-column:1/-1"><label>Price note / meeting point</label><input name="priceMeetingNote" value="<?= htmlspecialchars($program['priceMeetingNote'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Meet at Pak Meng Pier · Adult 650 THB · Child 550 THB"/></div>
      <div class="field" style="grid-column:1/-1"><label>Tour season</label><input name="season" value="<?= htmlspecialchars($program['season'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="1 Oct – 31 May · Departs daily"/></div>
      <div class="field" style="grid-column:1/-1"><label>Included in the package (one per line)</label><textarea name="inclusions" rows="6"><?= htmlspecialchars(implode("\n", $program['inclusions'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <?php if ($showInclusionsByDay): ?>
      <div class="field" style="grid-column:1/-1">
        <label>Included by day (4 days 3 nights package)</label>
        <textarea name="inclusionsByDay" rows="12" placeholder="Day 1&#10;3 nights accommodation&#10;Check-in per program&#10;&#10;Day 2&#10;Trang sea tour boat"><?= htmlspecialchars($inclusionsByDayText, ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="field-hint">Start a line with <strong>Day N</strong> followed by that day's items · leave a blank line between days</p>
      </div>
      <?php endif; ?>
      <div class="field" style="grid-column:1/-1">
        <label>Hotels &amp; room types (multi-day packages)</label>
        <textarea name="hotels" rows="12" placeholder="Chom Trang Hotel&#10;Deluxe room|2150&#10;Superior room|2250&#10;&#10;Maison Hotel&#10;Superior room|2250&#10;Deluxe room|Book"><?= htmlspecialchars($hotelsText, ENT_QUOTES, 'UTF-8') ?></textarea>
        <p class="field-hint">First line of each hotel = hotel name · following lines = room name|price per person (number) or Book · up to 5 rooms per hotel · leave a blank line between hotels</p>
      </div>
      <div class="field" style="grid-column:1/-1"><label>Age notes / price conditions (one per line)</label><textarea name="priceNotes" rows="4"><?= htmlspecialchars(implode("\n", $program['priceNotes'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea></div>
      <div class="field" style="grid-column:1/-1"><label>Warning / conditions text (bottom bar)</label><textarea name="warning" rows="3"><?= htmlspecialchars($program['warning'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-ghost" href="<?= htmlspecialchars($collectionMeta['listHref'], ENT_QUOTES, 'UTF-8') ?>">Back to list</a>
    <?php if (!$isNew): ?>
      <a class="btn btn-ghost" href="../program.html?id=<?= urlencode($program['id'] ?? '') ?>" target="_blank" rel="noopener">View detail page</a>
      <a class="btn btn-ghost" href="../program.html?id=<?= urlencode($program['id'] ?? '') ?>#pkg-book" target="_blank" rel="noopener">Test booking</a>
    <?php endif; ?>
  </div>
</form>

<?php tt_admin_footer(); ?>
