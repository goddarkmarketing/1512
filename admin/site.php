<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$site = $data['site'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facebookUrl = trim($_POST['facebookUrl'] ?? '');
    $facebookPageUrl = trim($_POST['facebookPageUrl'] ?? '');

    $oldSite = $data['site'] ?? [];
    $phone = trim($_POST['phone'] ?? '');
    $phone2 = trim($_POST['phone2'] ?? '');
    $phoneDisplay = trim($_POST['phoneDisplay'] ?? '');
    if ($phoneDisplay === '') {
        $phoneDisplay = implode(', ', array_values(array_filter([$phone, $phone2], static fn($v) => $v !== '')));
    }
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $whatsappUrl = trim($_POST['whatsappUrl'] ?? '');
    if ($whatsappUrl === '' && $whatsapp !== '') {
        $whatsappUrl = 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp);
    }

    $site = array_merge($oldSite, [
        'brand' => trim($_POST['brand'] ?? ''),
        'brandTh' => trim($_POST['brandTh'] ?? ''),
        'tagline' => trim($_POST['tagline'] ?? ''),
        'phone' => $phone,
        'phone2' => $phone2,
        'phoneDisplay' => $phoneDisplay,
        'whatsapp' => $whatsapp,
        'whatsappUrl' => $whatsappUrl,
        'lineId' => trim($_POST['lineId'] ?? ''),
        'lineUrl' => trim($_POST['lineUrl'] ?? ''),
        'facebookUrl' => $facebookUrl,
        'facebookPageUrl' => $facebookPageUrl,
        'tiktokUrl' => trim($_POST['tiktokUrl'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'addressFull' => trim($_POST['addressFull'] ?? ''),
        'hours' => trim($_POST['hours'] ?? ''),
        'popupEnabled' => isset($_POST['popupEnabled']),
        'popupHoursWeekday' => trim($_POST['popupHoursWeekday'] ?? ''),
        'popupHoursSunday' => trim($_POST['popupHoursSunday'] ?? ''),
        'lineQrImage' => trim($_POST['lineQrImage'] ?? ''),
        'mapEmbed' => trim($_POST['mapEmbed'] ?? ''),
    ]);

    if ($site['facebookPageUrl'] === '' && $facebookUrl !== '') {
        $resolved = tt_resolve_facebook_page_url($facebookUrl);
        if ($resolved !== '') {
            $site['facebookPageUrl'] = $resolved;
        }
    }

    $data['site'] = $site;
    if (tt_write_data($data)) {
        tt_set_flash('Site info saved — the data will update on the website immediately');
    } else {
        tt_set_flash('Save failed — check write permissions for data/site.json and assets/js/data-fallback.js', 'error');
    }
    header('Location: site.php');
    exit;
}

tt_admin_header('Site info', 'site');
$flash = tt_flash();
$flashType = tt_flash_type();
if ($flash): ?><div class="alert alert-<?= $flashType === 'error' ? 'error' : 'success' ?>"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post" class="card">
  <h2>Contact & brand</h2>
  <div class="grid-2">
    <div class="field"><label>Brand name (EN)</label><input name="brand" value="<?= htmlspecialchars($site['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Brand name (TH)</label><input name="brandTh" value="<?= htmlspecialchars($site['brandTh'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field" style="grid-column:1/-1"><label>Tagline</label><input name="tagline" value="<?= htmlspecialchars($site['tagline'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Phone number (primary)</label><input name="phone" id="site-phone" value="<?= htmlspecialchars($site['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/><p class="field-hint">Main click-to-call number</p></div>
    <div class="field"><label>Phone number (secondary)</label><input name="phone2" value="<?= htmlspecialchars($site['phone2'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Phone display text</label><input name="phoneDisplay" value="<?= htmlspecialchars($site['phoneDisplay'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="093-7414111, 080-6323483"/></div>
    <div class="field"><label>WhatsApp</label><input name="whatsapp" value="<?= htmlspecialchars($site['whatsapp'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="+66937414111"/></div>
    <div class="field"><label>WhatsApp URL</label><input name="whatsappUrl" value="<?= htmlspecialchars($site['whatsappUrl'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://wa.me/66937414111"/></div>
    <div class="field"><label>LINE ID</label><input name="lineId" value="<?= htmlspecialchars($site['lineId'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>LINE URL</label><input name="lineUrl" value="<?= htmlspecialchars($site['lineUrl'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Facebook URL (general link)</label><input name="facebookUrl" value="<?= htmlspecialchars($site['facebookUrl'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Facebook page URL (Feed)</label><input name="facebookPageUrl" value="<?= htmlspecialchars($site['facebookPageUrl'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.facebook.com/talaytrang"/><p class="field-hint">Used to embed the feed on the videos page — if left blank, the system will derive the page URL from the share link automatically</p></div>
    <div class="field"><label>TikTok URL</label><input name="tiktokUrl" value="<?= htmlspecialchars($site['tiktokUrl'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Short address</label><input name="address" value="<?= htmlspecialchars($site['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Full address</label><input name="addressFull" value="<?= htmlspecialchars($site['addressFull'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field"><label>Opening hours</label><input name="hours" value="<?= htmlspecialchars($site['hours'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
    <div class="field" style="grid-column:1/-1">
      <label><input type="checkbox" name="popupEnabled" value="1"<?= ($site['popupEnabled'] ?? true) ? ' checked' : '' ?>/> Show the contact popup when visitors enter the website</label>
    </div>
    <div class="field"><label>Opening hours (popup — Mon–Sat)</label><input name="popupHoursWeekday" value="<?= htmlspecialchars($site['popupHoursWeekday'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Monday - Saturday 07:00-21:00"/></div>
    <div class="field"><label>Opening hours (popup — Sunday)</label><input name="popupHoursSunday" value="<?= htmlspecialchars($site['popupHoursSunday'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Sunday 07:00-21:00"/></div>
    <div class="field" style="grid-column:1/-1"><label>LINE QR image (URL)</label><input name="lineQrImage" value="<?= htmlspecialchars($site['lineQrImage'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="assets/uploads/line-qr.png — if blank, the system generates a QR from the LINE URL automatically"/></div>
    <div class="field" style="grid-column:1/-1"><label>Google Maps Embed URL</label><input name="mapEmbed" value="<?= htmlspecialchars($site['mapEmbed'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
  </div>
  <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button></div>
</form>

<?php tt_admin_footer(); ?>
