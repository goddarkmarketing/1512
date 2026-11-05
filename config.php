<?php
/**
 * Talay Trang — CMS configuration
 * Change password: update ADMIN_PASSWORD then delete data/admin-credentials.json (if present) to rebuild the hash.
 */
declare(strict_types=1);

date_default_timezone_set('Asia/Bangkok');

define('TT_ROOT', __DIR__);
define('TT_DATA_FILE', TT_ROOT . '/data/site.json');
define('TT_UPLOAD_DIR', TT_ROOT . '/assets/uploads/');
define('TT_FALLBACK_JS', TT_ROOT . '/assets/js/data-fallback.js');
define('TT_CREDENTIALS_FILE', TT_ROOT . '/data/admin-credentials.json');
define('TT_BACKUP_DIR', TT_ROOT . '/data/backups');
define('TT_BACKUP_MAX_MANUAL', 30);
define('TT_BACKUP_MAX_AUTO', 15);

/** Default password — change immediately after install */
define('TT_ADMIN_USER', 'admin');
define('TT_ADMIN_PASSWORD', 'talaytrang2026');

/** Session */
define('TT_SESSION_NAME', 'tt_admin_session');

/**
 * Card checkout (Stripe). Leave empty until the live secret key is set.
 * Never put this key in site.json or frontend JavaScript.
 */
define('TT_STRIPE_SECRET_KEY', getenv('TT_STRIPE_SECRET_KEY') ?: '');
