<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $result = tt_backup_create();
        if ($result['ok']) {
            if (!empty($result['partial'])) {
                tt_set_flash('Text and data backed up — this host does not support ZIP, so images are not included in the file');
            } else {
                $count = (int)($result['uploadCount'] ?? 0);
                $msg = 'Full backup completed (text + uploaded images';
                $msg .= $count > 0 ? ' ' . $count . ' files)' : ')';
                tt_set_flash($msg);
            }
        } else {
            tt_set_flash($result['error'] ?? 'Backup failed, please try again', 'error');
        }
        header('Location: backup.php');
        exit;
    }

    if ($action === 'restore') {
        $file = basename(trim($_POST['file'] ?? ''));
        $confirm = trim($_POST['confirm'] ?? '');
        if ($confirm !== 'RESTORE') {
            tt_set_flash('Type the word RESTORE (as shown) to confirm', 'error');
            header('Location: backup.php');
            exit;
        }
        $result = tt_backup_restore($file);
        if ($result['ok']) {
            tt_set_flash('Data restored successfully — open the website and refresh to see the result');
        } else {
            tt_set_flash($result['error'] ?? 'Restore failed', 'error');
        }
        header('Location: backup.php');
        exit;
    }

    if ($action === 'delete') {
        $file = basename(trim($_POST['file'] ?? ''));
        if (tt_backup_delete($file)) {
            tt_set_flash('Backup deleted');
        } else {
            tt_set_flash('Delete failed', 'error');
        }
        header('Location: backup.php');
        exit;
    }
}

$backups = tt_backup_list();
$autoCount = count(array_filter($backups, fn($b) => $b['type'] === 'auto'));

tt_admin_header('Backup & restore data', 'backup');
$flash = tt_flash();
$flashType = tt_flash_type();
if ($flash): ?>
  <div class="alert alert-<?= $flashType === 'error' ? 'error' : 'success' ?>"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="card backup-guide">
  <h2>What is a backup?</h2>
  <p>It is like <strong>taking a screenshot to keep</strong> — it stores the text, phone numbers, prices, uploaded images and everything you edit in the admin as one set. If you make a mistake or data goes missing, you can <strong>roll back</strong>.</p>

  <h3 style="margin-top:20px;font-size:1rem">How to use (read in order)</h3>
  <ol class="backup-steps">
    <li><strong>Before making major website changes</strong> — click the blue 「Back up now」 button below</li>
    <li><strong>Keep a copy on your own device</strong> (recommended) — in the table below, click 「Download」 and store it in Google Drive or on your computer</li>
    <li><strong>Edit the website as usual</strong> — you can use the other admin menus right away</li>
    <li><strong>If you make a mistake</strong> — choose the date you want in the table, click 「Restore」 then type the word <strong>RESTORE</strong> to confirm</li>
  </ol>
  <p class="field-hint" style="margin-top:12px">💡 Automatic backups happen when you click 「Save」 in the various menus (not every minute) — the latest <?= (int)TT_BACKUP_MAX_AUTO ?> sets are kept · Click 「Back up now」 when you need a complete file including images</p>
</div>

<div class="card backup-create">
  <div class="backup-create__head">
    <h2>Back up now</h2>
    <p class="field-hint">Saves all data on the website as of now — including text and images uploaded to the system</p>
  </div>

  <form method="post" class="backup-create__form">
    <input type="hidden" name="action" value="create"/>

    <div class="backup-create__includes">
      <span class="backup-create__includes-label">Data included</span>
      <ul class="backup-create__tags">
        <li>Phone numbers & contact</li>
        <li>Tour programs</li>
        <li>Articles & reviews</li>
        <li>Menu & SEO</li>
        <li>Uploaded images</li>
      </ul>
    </div>

    <div class="backup-create__actions">
      <button type="submit" class="btn btn-primary">Back up now</button>
    </div>
  </form>
</div>

<div class="card">
  <h2>Automatic backup</h2>
  <p class="field-hint">Every time you click 「Save」 in the admin <strong>and the content has actually changed</strong>, the system keeps a copy of the text for you (no duplicate backup if you save again without changes) — not automatic every minute (images are not included — use 「Back up now」 for a complete file)</p>
  <p>Automatic copies right now: <strong><?= $autoCount ?></strong> sets (up to <?= (int)TT_BACKUP_MAX_AUTO ?> sets kept)</p>
</div>

<div class="card">
  <h2>Saved backups (<?= count($backups) ?> sets)</h2>
  <?php if (!$backups): ?>
    <p class="field-hint">No backups yet — click the 「Back up now」 button above first</p>
  <?php else: ?>
  <p class="field-hint" style="margin-bottom:16px">Choose a date close to the time you want to go back to</p>
  <table class="table">
    <thead>
      <tr>
        <th>Date / time</th>
        <th>Type</th>
        <th>File size</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($backups as $b): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($b['createdDisplay'], ENT_QUOTES, 'UTF-8') ?></strong>
            <?php if ($b['isZip']): ?><br><span class="field-hint">Text + uploaded images</span><?php endif; ?>
          </td>
          <td><?= htmlspecialchars($b['label'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars(tt_backup_format_bytes($b['size']), ENT_QUOTES, 'UTF-8') ?></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="backup-download.php?file=<?= urlencode($b['file']) ?>" title="Keep the file on your computer or Google Drive">Download</a>
              <button type="button" class="btn btn-ghost btn-sm" data-restore="<?= htmlspecialchars($b['file'], ENT_QUOTES, 'UTF-8') ?>" data-date="<?= htmlspecialchars($b['createdDisplay'], ENT_QUOTES, 'UTF-8') ?>" title="Roll the website back to this date">Restore</button>
              <form method="post" style="display:inline" onsubmit="return confirm('Delete this backup? (The current website will not change)')">
                <input type="hidden" name="action" value="delete"/>
                <input type="hidden" name="file" value="<?= htmlspecialchars($b['file'], ENT_QUOTES, 'UTF-8') ?>"/>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<div class="card" id="restore-panel" hidden>
  <h2>Confirm restore (roll back)</h2>
  <p class="field-hint" style="color:#b45309">
    The data on the website <strong>right now</strong> will be replaced with the data from the selected date<br>
    The system will save another backup of the current data before restoring — safe
  </p>
  <form method="post">
    <input type="hidden" name="action" value="restore"/>
    <input type="hidden" name="file" id="restore-file" value=""/>
    <div class="field">
      <label>Roll back to date</label>
      <input id="restore-file-display" readonly/>
    </div>
    <div class="field">
      <label>Type the word <strong>RESTORE</strong> to confirm</label>
      <input name="confirm" required placeholder="RESTORE" autocomplete="off"/>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Confirm — restore data</button>
      <button type="button" class="btn btn-ghost" id="restore-cancel">Cancel</button>
    </div>
  </form>
</div>

<script>
(function () {
  const panel = document.getElementById('restore-panel');
  const fileInput = document.getElementById('restore-file');
  const fileDisplay = document.getElementById('restore-file-display');
  document.querySelectorAll('[data-restore]').forEach((btn) => {
    btn.addEventListener('click', () => {
      fileInput.value = btn.getAttribute('data-restore');
      fileDisplay.value = btn.getAttribute('data-date') || btn.getAttribute('data-restore');
      panel.hidden = false;
      panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
  document.getElementById('restore-cancel')?.addEventListener('click', () => {
    panel.hidden = true;
  });
})();
</script>

<?php tt_admin_footer(); ?>
