<?php
declare(strict_types=1);

/**
 * @return array<string, array{
 *   lead: string,
 *   items: list<string>,
 *   preview?: string,
 *   previewLabel?: string,
 *   visuals?: list<array{
 *     caption?: string,
 *     page: string,
 *     selector: string,
 *     maxHeight?: int,
 *     waitChildren?: bool,
 *     scope?: string
 *   }>
 * }>
 */
function tt_admin_page_help_map(): array
{
    return [
        'dashboard' => [
            'lead' => 'This page is an overview of the admin system — it is not shown directly on the website',
            'items' => [
                'Use the left menu to edit each section of the website',
                'After saving, the data updates on the website immediately',
            ],
        ],
        'site' => [
            'lead' => 'Contact info & brand — used in many places on the website',
            'items' => [
                'Footer on every page — phone, LINE, Facebook, TikTok, address, opening hours',
                '「Book via LINE」 and 「Call now」 buttons in the menu / mobile bar',
                'About page — map, address, opening hours, LINE',
                'Booking page — opens LINE after the request is submitted',
                'Videos page — embeds the Facebook feed (page URL)',
            ],
            'preview' => '../about.html',
            'previewLabel' => 'View Footer / About',
            'visuals' => [
                [
                    'caption' => 'Footer on every page — phone, LINE, social, address',
                    'page' => '../index.html',
                    'selector' => '#site-footer',
                    'maxHeight' => 200,
                    'waitChildren' => true,
                ],
                [
                    'caption' => 'About page — map & contact info',
                    'page' => '../about.html',
                    'selector' => '.office',
                    'maxHeight' => 220,
                ],
            ],
        ],
        'nav' => [
            'lead' => 'Top link menu (and mobile menu)',
            'items' => [
                'Shown on every page — Home, Boats, Programs, Booking, Articles, etc.',
                'Order and names follow the list settings',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Top menu bar on every page',
                'page' => '../index.html',
                'selector' => '#site-header',
                'maxHeight' => 72,
            ]],
        ],
        'hero' => [
            'lead' => 'Banner slides at the top of the home page',
            'items' => [
                'Slideshow images rotate automatically below the menu (index.html)',
                'Each slide = image + alt description',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Home page banner slides (below the menu)',
                'page' => '../index.html',
                'selector' => '#hero-slider',
                'scope' => 'section',
                'maxHeight' => 220,
                'waitChildren' => true,
            ]],
        ],
        'services' => [
            'lead' => 'Service bar (Pills) on the home page',
            'items' => [
                'Icon + service name, scrolls horizontally',
                'Click to go to the configured page (boats, booking, LINE, etc.)',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Service bar (Pills) on the home page',
                'page' => '../index.html',
                'selector' => '#home-services',
                'scope' => 'section',
                'maxHeight' => 280,
                'waitChildren' => true,
            ]],
        ],
        'boats' => [
            'lead' => 'Boat types — boats.html page (day trip package cards on the home page come from Tour packages)',
            'items' => [
                'Day trip packages Section heading on the home page — edit in the 「Section headings」 menu',
                '「Boat types」 page — details for each boat type',
                '8 package cards on the home page — edit in the 「Tour packages」 menu (# code on the card)',
            ],
            'preview' => '../boats.html',
            'visuals' => [
                [
                    'caption' => 'Hero + boat list — boats.html page',
                    'page' => '../boats.html',
                    'selector' => '#boats-list',
                    'scope' => 'section',
                    'maxHeight' => 320,
                    'waitChildren' => true,
                ],
                [
                    'caption' => 'Day trip package cards on the home page',
                    'page' => '../index.html',
                    'selector' => '#home-boats',
                    'scope' => 'section',
                    'maxHeight' => 320,
                    'waitChildren' => true,
                ],
            ],
        ],
        'programs' => [
            'lead' => 'Tour packages — home page, programs.html and program.html (details)',
            'items' => [
                'Home page cards + programs.html page — name, cover image, price, code',
                'Detail page program.html — click 「Edit」 → edit the 6-image gallery, tour program, prices, inclusions',
                'Supports 3 categories: Day trip · 2 days 1 night · 3 days 2 nights',
            ],
            'preview' => '../program.html?id=dl001',
            'previewLabel' => 'View detail page (example)',
            'visuals' => [
                [
                    'caption' => 'Package cards on the home page',
                    'page' => '../index.html',
                    'selector' => '#home-boats',
                    'scope' => 'section',
                    'maxHeight' => 320,
                    'waitChildren' => true,
                ],
                [
                    'caption' => 'Detail page program.html — gallery + content',
                    'page' => '../program.html?id=dl001',
                    'selector' => '#pkg-gallery',
                    'scope' => 'main',
                    'maxHeight' => 280,
                    'waitChildren' => true,
                ],
            ],
        ],
        'options' => [
            'lead' => 'Extra options on the boat booking page',
            'items' => [
                'Food, guide, photographer, diving, shuttle, etc.',
                'Customers add them when calculating the price (booking.html)',
            ],
            'preview' => '../booking.html?step=trip',
            'visuals' => [[
                'caption' => 'Extra options in the booking form',
                'page' => '../booking.html?step=trip',
                'selector' => '#option-choices',
                'scope' => 'section',
                'maxHeight' => 280,
                'waitChildren' => true,
            ]],
        ],
        'reviews' => [
            'lead' => 'Customer reviews — reviews section on the home page',
            'items' => [
                'Customer testimonial cards (slides on mobile)',
                'Name, trip, text, stars',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Customer reviews section on the home page',
                'page' => '../index.html',
                'selector' => '#home-reviews',
                'scope' => 'section',
                'maxHeight' => 340,
                'waitChildren' => true,
            ]],
        ],
        'videos' => [
            'lead' => 'TikTok / Facebook videos — videos.html page',
            'items' => [
                'Video cards + links to TikTok / Facebook',
                'Embedded feed from TikTok and the Facebook page',
            ],
            'preview' => '../videos.html',
            'visuals' => [[
                'caption' => 'TikTok video section on the web page',
                'page' => '../videos.html',
                'selector' => '#tiktok-feed',
                'scope' => 'section',
                'maxHeight' => 320,
                'waitChildren' => true,
            ]],
        ],
        'why' => [
            'lead' => 'Highlights 「Why book with us」',
            'items' => [
                'Shown on the About page (and related sections on the home page)',
                'Icon + title + short description',
            ],
            'preview' => '../about.html',
            'visuals' => [[
                'caption' => '4 highlights on the About page',
                'page' => '../about.html',
                'selector' => '#why-list',
                'scope' => 'section',
                'maxHeight' => 280,
                'waitChildren' => true,
            ]],
        ],
        'steps' => [
            'lead' => 'Booking steps — home page',
            'items' => [
                '4 steps: Choose boat → Program → Calculate price → LINE',
                'Located above the reviews section or CTA on the home page',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => '4 booking steps on the home page',
                'page' => '../index.html',
                'selector' => '#home-steps',
                'scope' => 'section',
                'maxHeight' => 280,
                'waitChildren' => true,
            ]],
        ],
        'articles' => [
            'lead' => 'Articles — articles.html page + details',
            'items' => [
                'Article list (thumbnail, title, excerpt)',
                'article.html page — full content by slug',
            ],
            'preview' => '../articles.html',
            'visuals' => [[
                'caption' => 'Article list on the web page',
                'page' => '../articles.html',
                'selector' => '#articles-list',
                'scope' => 'section',
                'maxHeight' => 320,
                'waitChildren' => true,
            ]],
        ],
        'about' => [
            'lead' => 'Content of the 「About us」 page',
            'items' => [
                'Hero, story, credibility numbers, CTA',
                'Address / map / phone / LINE are pulled automatically from 「Site info」',
                'Story grid images are edited in 「Images Registry」 (about1–3)',
            ],
            'preview' => '../about.html',
            'visuals' => [
                [
                    'caption' => 'About page Hero',
                    'page' => '../about.html',
                    'selector' => '.hero-sub',
                    'maxHeight' => 180,
                ],
                [
                    'caption' => 'Story grid + 3 image slots',
                    'page' => '../about.html',
                    'selector' => '#story-grid',
                    'scope' => 'section',
                    'maxHeight' => 280,
                    'waitChildren' => true,
                ],
            ],
        ],
        'deals' => [
            'lead' => 'Custom Trip promotion card on the home page',
            'items' => [
                'Special card in the deal section (after programs / boats)',
                'Show/hide, image, price, badges, booking link',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Custom Trip card on the home page (boats section)',
                'page' => '../index.html',
                'selector' => '#home-boats',
                'scope' => 'section',
                'maxHeight' => 340,
                'waitChildren' => true,
            ]],
        ],
        'seo' => [
            'lead' => 'Title & Meta for each page (Google / social sharing)',
            'items' => [
                'Browser tab title, description, og:image',
                'Not visible directly on the web page, but affects search and link sharing',
            ],
            'preview' => '../index.html',
            'visuals' => [[
                'caption' => 'Web pages that use title / meta (home page example)',
                'page' => '../index.html',
                'selector' => '#hero-slider',
                'maxHeight' => 160,
                'waitChildren' => true,
            ]],
        ],
        'images' => [
            'lead' => 'Image library (Registry) — sub-page Hero & About background images',
            'items' => [
                'Sub-page Hero: heroBoats, heroPrograms, heroAbout, etc.',
                'About images: about1, about2, about3',
                'Use a URL or path from 「Upload image」',
            ],
            'preview' => '../boats.html',
            'previewLabel' => 'View sub-page Hero',
            'visuals' => [[
                'caption' => 'Sub-page Hero (boats page example)',
                'page' => '../boats.html',
                'selector' => '.hero-sub',
                'maxHeight' => 180,
            ]],
        ],
        'media' => [
            'lead' => 'Upload images to the website — used together with the URL fields in other menus',
            'items' => [
                'Not shown as a separate web page — it is a file storage tool',
                'After uploading, copy the path and paste it into the edit form',
                'Or click the 「Upload」 button next to the URL field directly',
            ],
            'visuals' => [[
                'caption' => 'Uploaded images appear in cards / banners like this',
                'page' => '../index.html',
                'selector' => '#home-programs',
                'scope' => 'section',
                'maxHeight' => 320,
                'waitChildren' => true,
            ]],
        ],
    ];
}

/**
 * @return array<string, array{
 *   lead: string,
 *   items: list<string>,
 *   preview?: string,
 *   visual: array{page:string,selector:string,maxHeight?:int,waitChildren?:bool,scope?:string,caption?:string}
 * }>
 */
function tt_home_section_help_map(): array
{
    return [
        'hero' => [
            'lead' => 'Heading on the banner slides — home page',
            'items' => ['Text over the top slide image', 'Does not include the scrolling service buttons below'],
            'preview' => '../index.html',
            'visual' => [
                'caption' => 'Position on the home page',
                'page' => '../index.html',
                'selector' => '#hero-slider .overlay-head',
                'maxHeight' => 200,
                'waitChildren' => true,
            ],
        ],
        'boats' => [
            'lead' => 'Day trip packages Section heading — home page',
            'items' => ['Above the 8 package cards (4+4)', 'Cards and # codes are edited in the Tour packages menu'],
            'preview' => '../index.html#boats',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="boats"]',
                'scope' => 'section',
                'maxHeight' => 180,
            ],
        ],
        'programs' => [
            'lead' => 'Popular programs Section heading — home page',
            'items' => ['Above the program cards', 'Program cards are edited in the Tour packages menu'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '#home-section-programs .section-head',
                'scope' => 'section',
                'maxHeight' => 200,
            ],
        ],
        'booking' => [
            'lead' => 'Booking steps Section heading — home page',
            'items' => ['Above the 4 booking steps', 'Steps are edited in the Booking steps menu'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="booking"]',
                'scope' => 'section',
                'maxHeight' => 180,
            ],
        ],
        'reviews' => [
            'lead' => 'Reviews Section heading — home page',
            'items' => ['Above the review cards', 'Reviews are edited in the Reviews menu'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="reviews"]',
                'scope' => 'section',
                'maxHeight' => 180,
            ],
        ],
        'videos' => [
            'lead' => 'TikTok Section heading — home page',
            'items' => ['Above the video clips', 'Clips are edited in the Videos menu'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="videos"]',
                'scope' => 'section',
                'maxHeight' => 180,
            ],
        ],
        'office' => [
            'lead' => 'Office Section heading — home page',
            'items' => ['Top heading + office info box', 'Map / phone are pulled from Site info'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="office"]',
                'scope' => 'section',
                'maxHeight' => 200,
            ],
        ],
        'cta' => [
            'lead' => 'CTA Section heading at the bottom of the page — home page',
            'items' => ['Text before the booking button at the bottom of the home page'],
            'preview' => '../index.html',
            'visual' => [
                'page' => '../index.html',
                'selector' => '[data-tt-home-section="cta"]',
                'maxHeight' => 200,
            ],
        ],
    ];
}

function tt_admin_page_help_visual_figure(array $visual): string
{
    $caption = trim((string)($visual['caption'] ?? ''));
    $page = (string)($visual['page'] ?? '');
    $selector = (string)($visual['selector'] ?? '');
    if ($page === '' || $selector === '') {
        return '';
    }
    $maxHeight = (int)($visual['maxHeight'] ?? 200);
    $waitChildren = !empty($visual['waitChildren']) ? '1' : '0';
    $scope = trim((string)($visual['scope'] ?? ''));

    $html = '<figure class="admin-page-help-figure admin-page-help-live-wrap"'
        . ' data-page="' . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-selector="' . htmlspecialchars($selector, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-max-height="' . $maxHeight . '"'
        . ' data-wait-children="' . $waitChildren . '"'
        . ($scope !== '' ? ' data-scope="' . htmlspecialchars($scope, ENT_QUOTES, 'UTF-8') . '"' : '')
        . '>'
        . '<div class="admin-page-help-live">'
        . '<div class="admin-page-help-live-clip">'
        . '<div class="admin-page-help-live-stage">'
        . '<iframe class="admin-page-help-iframe" title="Web page preview" tabindex="-1" loading="eager"></iframe>'
        . '</div></div>'
        . '<div class="admin-page-help-live-loading">Loading preview…</div>'
        . '</div>';
    if ($caption !== '') {
        $html .= '<figcaption>' . htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') . '</figcaption>';
    }
    return $html . '</figure>';
}

function tt_admin_page_help(?string $pageId): ?array
{
    if ($pageId === '' || $pageId === 'password' || $pageId === 'section-headings') {
        return null;
    }
    $map = tt_admin_page_help_map();
    return $map[$pageId] ?? null;
}

function tt_render_admin_section_help(string $sectionKey): string
{
    $map = tt_home_section_help_map();
    $help = $map[$sectionKey] ?? null;
    if ($help === null) {
        return '';
    }

    $visualsHtml = tt_admin_page_help_visual_figure($help['visual'] ?? []);
    $hasVisuals = $visualsHtml !== '';
    $popoverClass = 'admin-page-help-popover' . ($hasVisuals ? ' has-visuals' : '');

    $items = '';
    foreach ($help['items'] as $item) {
        $items .= '<li>' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</li>';
    }

    $preview = '';
    if (!empty($help['preview'])) {
        $preview = '<p class="admin-page-help-foot"><a href="'
            . htmlspecialchars($help['preview'], ENT_QUOTES, 'UTF-8')
            . '" target="_blank" rel="noopener">Open on website ↗</a></p>';
    }

    return '<span class="admin-page-help admin-page-help--inline">'
        . '<button type="button" class="admin-page-help-btn" aria-label="See where this appears on the website">?</button>'
        . '<div class="' . $popoverClass . '" role="tooltip">'
        . $visualsHtml
        . '<p class="admin-page-help-lead">' . htmlspecialchars($help['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<ul class="admin-page-help-list">' . $items . '</ul>'
        . $preview
        . '</div></span>';
}

function tt_render_admin_page_help(?string $pageId): string
{
    $help = tt_admin_page_help($pageId);
    if ($help === null) {
        return '';
    }

    $visualsHtml = '';
    foreach ($help['visuals'] ?? [] as $visual) {
        $visualsHtml .= tt_admin_page_help_visual_figure($visual);
    }

    $hasVisuals = $visualsHtml !== '';
    $popoverClass = 'admin-page-help-popover' . ($hasVisuals ? ' has-visuals' : '');

    $items = '';
    foreach ($help['items'] as $item) {
        $items .= '<li>' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</li>';
    }

    $preview = '';
    if (!empty($help['preview'])) {
        $label = $help['previewLabel'] ?? 'Open on website';
        $preview = '<p class="admin-page-help-foot"><a href="'
            . htmlspecialchars($help['preview'], ENT_QUOTES, 'UTF-8')
            . '" target="_blank" rel="noopener">'
            . htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
            . ' ↗</a></p>';
    }

    return '<span class="admin-page-help">'
        . '<button type="button" class="admin-page-help-btn" aria-label="See where this appears on the website">?</button>'
        . '<div class="' . $popoverClass . '" role="tooltip">'
        . $visualsHtml
        . '<p class="admin-page-help-lead">' . htmlspecialchars($help['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<ul class="admin-page-help-list">' . $items . '</ul>'
        . $preview
        . '</div></span>';
}
