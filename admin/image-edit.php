<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
tt_require_admin();

$data = tt_read_data();
$images = $data['images'] ?? [];
$key = trim($_GET['key'] ?? '');
$entry = ($key !== '' && array_key_exists($key, $images)) ? $images[$key] : null;
$isNew = $entry === null;
$isArray = is_array($entry);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldKey = trim($_POST['old_key'] ?? '');
    $newKey = trim($_POST['key'] ?? '');
    if ($newKey === '') {
        tt_set_flash('Please enter a Key');
        header('Location: image-edit.php' . ($oldKey !== '' ? '?key=' . urlencode($oldKey) : ''));
        exit;
    }

    $type = $_POST['type'] ?? 'single';
    $raw = trim($_POST['value'] ?? '');

    if ($type === 'array') {
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $value = array_values(array_filter(array_map('trim', $lines)));
        if (!$value && str_contains($raw, ',')) {
            $value = array_values(array_filter(array_map('trim', explode(',', $raw))));
        }
    } else {
        $value = $raw;
    }

    if ($oldKey !== '' && $oldKey !== $newKey) {
        unset($images[$oldKey]);
    }
    if ($oldKey === '' || $oldKey !== $newKey) {
        if (array_key_exists($newKey, $images) && $oldKey !== $newKey) {
            tt_set_flash('This Key already exists — use a different name');
            header('Location: image-edit.php?key=' . urlencode($oldKey !== '' ? $oldKey : $newKey));
            exit;
        }
    }

    $images[$newKey] = $value;
    $data['images'] = $images;
    tt_write_data($data);
    tt_set_flash('Image saved');
    header('Location: image-edit.php?key=' . urlencode($newKey));
    exit;
}

function tt_image_preview_url_edit(mixed $val): string
{
    $url = is_array($val) ? ($val[0] ?? '') : (string)$val;
    if ($url === '') {
        return '';
    }
    if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
        return $url;
    }
    return '../' . ltrim($url, '/');
}

$valueText = '';
if (is_array($entry)) {
    $valueText = implode("\n", $entry);
} elseif ($entry !== null) {
    $valueText = (string)$entry;
}

$pageTitle = $isNew ? 'Add image' : 'Edit image';
tt_admin_header($pageTitle, 'images');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Images (IMAGES Registry)</h2>
    <input type="hidden" name="old_key" value="<?= htmlspecialchars($isNew ? '' : $key, ENT_QUOTES, 'UTF-8') ?>"/>

    <?php
    $preview = $entry !== null ? tt_image_preview_url_edit($entry) : '';
    if ($preview): ?>
      <img src="<?= htmlspecialchars($preview, ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-width:280px;border-radius:10px;margin-bottom:16px;border:1px solid var(--adm-border)"/>
    <?php endif; ?>

    <div class="grid-2">
      <div class="field"><label>Key</label><input name="key" id="image-key" required value="<?= htmlspecialchars($isNew ? '' : $key, ENT_QUOTES, 'UTF-8') ?>" placeholder="heroHome, boatLongtail, videoThumbs"/></div>
      <div class="field"><label>Type</label>
        <select name="type" id="image-type">
          <option value="single"<?= !$isArray ? ' selected' : '' ?>>Single URL</option>
          <option value="array"<?= $isArray ? ' selected' : '' ?>>Multiple URLs (array)</option>
        </select>
      </div>
      <div id="image-value-wrap" style="grid-column:1/-1">
        <?php if ($isArray): ?>
          <?php tt_render_image_url_field('One URL per line', 'value', $valueText, [
              'id' => 'image-value',
              'multiline' => true,
              'append' => true,
              'key' => $isNew ? null : $key,
          ]); ?>
        <?php else: ?>
          <?php tt_render_image_url_field('URL / path', 'value', $valueText, [
              'id' => 'image-value',
              'key' => $isNew ? null : $key,
          ]); ?>
        <?php endif; ?>
      </div>
    </div>

    <p class="field-hint">Use a URL from Pexels or a path within the website — click the upload button to fill in the path automatically</p>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="images.php">Back</a>
      <a class="btn btn-ghost" href="media.php">Upload image</a>
      <?php if ($preview): ?>
        <a class="btn btn-ghost" href="<?= htmlspecialchars($preview, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">View image</a>
      <?php endif; ?>
      <?php if (!$isNew): ?>
        <a class="btn btn-danger" href="images.php?delete=<?= urlencode($key) ?>" onclick="return confirm('Delete this key?')">Delete this entry</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<script>
(function () {
  const typeEl = document.getElementById('image-type');
  const wrap = document.getElementById('image-value-wrap');
  const keyEl = document.getElementById('image-key');
  if (!typeEl || !wrap) return;

  const hints = <?= tt_image_size_hints_json() ?>;

  function sizeSuffixForKey(key) {
    key = (key || '').trim();
    if (!key) return '';
    let hint = hints[key] || '';
    if (!hint && key.startsWith('hero') && key !== 'heroSlides') hint = hints.heroSub || '';
    if (!hint && key.startsWith('program')) hint = hints.programCard || '';
    if (!hint && key.startsWith('boat')) hint = hints.boatLongtail || '';
    if (!hint && key.startsWith('svc')) hint = hints.servicePill || '';
    if (!hint && key.startsWith('about')) hint = hints[key] || hints.about2 || '';
    const m = hint.match(/^(\d+×\d+ px)/);
    return m ? 'Recommended ' + m[1] : '';
  }

  function baseLabel(isArray) {
    return isArray ? 'One URL per line' : 'URL / path';
  }

  function fullLabel(isArray) {
    const base = baseLabel(isArray);
    const suffix = sizeSuffixForKey(keyEl ? keyEl.value : '');
    return suffix ? base + ' — ' + suffix : base;
  }

  function currentValue() {
    const input = wrap.querySelector('.image-url-input');
    return input ? input.value : '';
  }

  function rebuildValueField() {
    const isArray = typeEl.value === 'array';
    const val = currentValue();
    wrap.innerHTML = '';
    wrap.appendChild(ttBuildImageUrlField({
      label: fullLabel(isArray),
      labelId: 'image-value-label',
      name: 'value',
      id: 'image-value',
      value: isArray ? val : (val.split('\n')[0] || val.replace(/,/g, '').trim()),
      multiline: isArray,
      append: isArray,
      rows: 8,
      gridColumn: '1/-1',
    }));
  }

  typeEl.addEventListener('change', rebuildValueField);
  if (keyEl) {
    keyEl.addEventListener('input', () => {
      const label = wrap.querySelector('label');
      if (label) label.textContent = fullLabel(typeEl.value === 'array');
    });
  }
})();
</script>

<?php tt_admin_footer(); ?>
