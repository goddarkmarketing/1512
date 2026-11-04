<?php
declare(strict_types=1);

/**
 * Image size recommendations — based on the website CSS (object-fit: cover)
 * Used in the admin as guidance only
 */
function tt_image_size_hint(?string $key = null, ?string $context = null): string
{
    if ($key !== null && $key !== '') {
        $byKey = tt_image_size_by_key();
        if (isset($byKey[$key])) {
            return $byKey[$key];
        }
        if (str_starts_with($key, 'hero') && $key !== 'heroSlides') {
            return $byKey['heroSub'] ?? tt_image_size_by_context()['hero_sub'];
        }
        if (str_starts_with($key, 'program')) {
            return $byKey['programCard'] ?? tt_image_size_by_context()['program'];
        }
        if (str_starts_with($key, 'boat')) {
            return $byKey['boatDetail'] ?? tt_image_size_by_context()['boat'];
        }
        if (str_starts_with($key, 'svc')) {
            return $byKey['servicePill'] ?? tt_image_size_by_context()['service'];
        }
        if (str_starts_with($key, 'about')) {
            return $byKey[$key === 'about1' ? 'about1' : 'aboutSmall'] ?? '';
        }
    }

    if ($context !== null && isset(tt_image_size_by_context()[$context])) {
        return tt_image_size_by_context()[$context];
    }

    return 'JPG/PNG/WebP — upload, then copy the path into the URL field';
}

/** @return array<string, string> */
function tt_image_size_by_key(): array
{
    return [
        'heroSub' => '1920×720 px (wide) — Hero for all sub-pages (horizontal crop)',
        'heroHome' => '1920×720 px — Home page Hero (Registry fallback)',
        'heroBoats' => '1920×720 px — Boat types page Hero',
        'heroPrograms' => '1920×720 px — Programs page Hero',
        'heroBooking' => '1920×720 px — Boat booking page Hero',
        'heroArticles' => '1920×720 px — Articles page Hero',
        'heroVideos' => '1920×720 px — Videos page Hero',
        'heroAbout' => '1920×720 px — About us page Hero',
        'heroContact' => '1920×720 px — Contact page Hero',
        'boatLongtail' => '1200×900 px (4:3) — Long-tail boat image (Registry)',
        'boatSpeedboat' => '1200×900 px (4:3) — Speed Boat image (Registry)',
        'boatLarge' => '1200×900 px (4:3) — Large boat image (Registry)',
        'programCard' => '1200×750 px (16:10) — Program card on the home page / programs',
        'programFourIslands' => '1200×750 px (16:10) — 4-island program (Registry)',
        'programKradan' => '1200×750 px (16:10) — Koh Kradan (Registry)',
        'programDiving' => '1200×750 px (16:10) — Diving (Registry)',
        'programEmerald' => '1200×750 px (16:10) — Emerald Cave (Registry)',
        'programPrivate' => '1200×750 px (16:10) — Private charter (Registry)',
        'about1' => '800×1000 px (portrait) — Large left image in the About page story grid',
        'about2' => '600×400 px — Small top image in the About page story grid',
        'about3' => '600×400 px — Small bottom image in the About page story grid',
        'servicePill' => '900×600 px (3:2) — Service image (Registry)',
        'videoThumbs' => '800×450 px — Video thumbnails in the Registry (one per line)',
        'local' => '1200×800 px — Local gallery (one per line)',
    ];
}

/** @return array<string, string> */
function tt_image_size_by_context(): array
{
    return [
        'hero_slide' => '1920×700 px (~2.75:1) — Home banner slide (full width)',
        'hero_sub' => '1920×720 px — Sub-page banner (height ~360px on desktop)',
        'program' => '1200×750 px (16:10) — Program card (auto-cropped)',
        'boat' => '1200×900 px (4:3) — Boat image on the Boat types page',
        'deal' => '1200×900 px (4:3) — Home page promotion card',
        'article_cover' => '1600×1000 px (16:10) — Article cover image (detail page)',
        'article_thumb' => '800×500 px (16:10) — Thumbnail in the article list',
        'video_thumb' => '720×1280 px (9:16) — TikTok / video cover (portrait)',
        'og' => '1200×630 px — Facebook / LINE share image (og:image)',
        'service' => '900×600 px (3:2) — Service image',
        'upload' => 'Depends on usage — see the table below before uploading',
    ];
}

/** @return list<array{label: string, size: string, ratio: string, where: string}> */
function tt_image_size_guide_rows(): array
{
    return [
        [
            'label' => 'Home banner slide',
            'size' => '1920×700 px',
            'ratio' => '~2.75 : 1',
            'where' => 'Home banner menu → hero-edit',
        ],
        [
            'label' => 'Sub-page Hero (all pages)',
            'size' => '1920×720 px',
            'ratio' => 'Wide ~8:3',
            'where' => 'Images (Registry) → heroBoats, heroPrograms, …',
        ],
        [
            'label' => 'Program card',
            'size' => '1200×750 px',
            'ratio' => '16 : 10',
            'where' => 'Tour programs menu → Edit → Image URL',
        ],
        [
            'label' => 'Boat image (details)',
            'size' => '1200×900 px',
            'ratio' => '4 : 3',
            'where' => 'Boat types menu → Edit → Image URL',
        ],
        [
            'label' => 'Home page promotion card',
            'size' => '1200×900 px',
            'ratio' => '4 : 3',
            'where' => 'Home cards menu → Custom Trip',
        ],
        [
            'label' => 'Article — cover image',
            'size' => '1600×1000 px',
            'ratio' => '16 : 10',
            'where' => 'Articles menu → Edit → Cover image',
        ],
        [
            'label' => 'Article — thumbnail',
            'size' => '800×500 px',
            'ratio' => '16 : 10',
            'where' => 'Articles menu → Edit → Thumbnail',
        ],
        [
            'label' => 'Video / TikTok cover',
            'size' => '720×1280 px',
            'ratio' => '9 : 16',
            'where' => 'Videos menu → Edit → Cover image',
        ],
        [
            'label' => 'About — large image',
            'size' => '800×1000 px',
            'ratio' => '4 : 5',
            'where' => 'Registry → about1',
        ],
        [
            'label' => 'About — small images',
            'size' => '600×400 px',
            'ratio' => '3 : 2',
            'where' => 'Registry → about2, about3',
        ],
        [
            'label' => 'SEO / social sharing',
            'size' => '1200×630 px',
            'ratio' => '1.91 : 1',
            'where' => 'SEO / Meta menu → og:image',
        ],
    ];
}

/** @return array<string, array{size: string, ratio: string, note: string}> */
function tt_image_size_meta_map(): array
{
    return [
        'hero_slide' => ['size' => '1920×700 px', 'ratio' => '~2.75 : 1', 'note' => 'Home banner slide — full-screen width'],
        'hero_sub' => ['size' => '1920×720 px', 'ratio' => 'Wide ~8:3', 'note' => 'Sub-page banner for all pages'],
        'program' => ['size' => '1200×750 px', 'ratio' => '16 : 10', 'note' => 'Program card on the home page / programs page'],
        'boat' => ['size' => '1200×900 px', 'ratio' => '4 : 3', 'note' => 'Boat image on the Boat types page'],
        'deal' => ['size' => '1200×900 px', 'ratio' => '4 : 3', 'note' => 'Custom Trip promotion card on the home page'],
        'article_cover' => ['size' => '1600×1000 px', 'ratio' => '16 : 10', 'note' => 'Article cover image (detail page)'],
        'article_thumb' => ['size' => '800×500 px', 'ratio' => '16 : 10', 'note' => 'Thumbnail in the article list'],
        'video_thumb' => ['size' => '720×1280 px', 'ratio' => '9 : 16', 'note' => 'TikTok / video cover (portrait)'],
        'og' => ['size' => '1200×630 px', 'ratio' => '1.91 : 1', 'note' => 'Facebook / LINE share image'],
        'service' => ['size' => '900×600 px', 'ratio' => '3 : 2', 'note' => 'Service image'],
    ];
}

/** @return array{size: string, ratio: string, note: string}|null */
function tt_image_size_meta(?string $key = null, ?string $context = null): ?array
{
    if ($context !== null && isset(tt_image_size_meta_map()[$context])) {
        return tt_image_size_meta_map()[$context];
    }

    if ($key !== null && $key !== '') {
        if (str_starts_with($key, 'hero') && $key !== 'heroSlides') {
            return tt_image_size_meta_map()['hero_sub'];
        }
        if (str_starts_with($key, 'program')) {
            return tt_image_size_meta_map()['program'];
        }
        if (str_starts_with($key, 'boat')) {
            return tt_image_size_meta_map()['boat'];
        }
        if ($key === 'about1') {
            return ['size' => '800×1000 px', 'ratio' => '4 : 5', 'note' => 'Large left image in the About page story grid'];
        }
        if ($key === 'about2' || $key === 'about3') {
            return ['size' => '600×400 px', 'ratio' => '3 : 2', 'note' => 'Small image in the About page story grid'];
        }
    }

    return null;
}

function tt_image_size_label(?string $key = null, ?string $context = null): string
{
    $meta = tt_image_size_meta($key, $context);
    return $meta ? 'Recommended ' . $meta['size'] : '';
}

function tt_render_image_size_hint(?string $key = null, ?string $context = null): string
{
    $meta = tt_image_size_meta($key, $context);
    if ($meta === null) {
        $text = tt_image_size_hint($key, $context);
        if ($text === '') {
            return '';
        }
        return '<div class="alert alert-info image-size-note"><strong>Image size:</strong> '
            . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</div>';
    }

    return '<div class="alert alert-info image-size-note">'
        . '<strong>📐 Recommended image size:</strong> '
        . '<span class="image-size-dim">' . htmlspecialchars($meta['size'], ENT_QUOTES, 'UTF-8') . '</span>'
        . ' <span class="image-size-ratio">(' . htmlspecialchars($meta['ratio'], ENT_QUOTES, 'UTF-8') . ')</span>'
        . '<br><span class="image-size-desc">' . htmlspecialchars($meta['note'], ENT_QUOTES, 'UTF-8') . '</span>'
        . ' · JPG/PNG/WebP · auto center-crop'
        . ' · <a href="media.php">View all size tables</a>'
        . '</div>';
}

/** @return string JSON for updating the hint by key in image-edit */
function tt_image_size_hints_json(): string
{
    return json_encode(tt_image_size_by_key(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
}
