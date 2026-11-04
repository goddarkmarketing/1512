# Talay Trang — Admin Panel (CMS)

## URL

| Page | URL (XAMPP) |
|------|-------------|
| **Admin Login** | http://localhost/1495/admin/login.php |
| **Dashboard** | http://localhost/1495/admin/ |
| **Data API** | http://localhost/1495/api/data.php |

## First login

- **Username:** `admin`
- **Password:** `talaytrang2026`

Change the password immediately in the **Change password** menu

## What you can edit

| Admin menu | What it edits |
|------------|---------------|
| Site info | Phone, LINE, Facebook, TikTok, address, map |
| Navigation menu | Main menu links |
| Home banner | Hero slides |
| Services (Pills) | Service buttons on the Hero |
| Boat types | Long-tail / Speed / Large boats + prices |
| Tour packages | All programs |
| Booking options | Food, guide, shuttle, etc. (booking page) |
| Reviews | Customer reviews |
| Videos | TikTok cards |
| Highlights | Why us |
| Booking steps | The 4 steps |
| Articles | Add / edit / delete + Markdown |
| About page | Hero, story, Trust strip, CTA |
| Home cards | Custom Trip card + boat & program ratings / reviews |
| SEO / Meta | title, description, og:tags for every page |
| Images (Registry) | Hero, boat cards, programs (IMAGES) |
| Upload image | Upload to `assets/uploads/` |

## File structure

```
data/site.json          ← all website data (edited via admin)
data/admin-credentials.json  ← password (auto-generated)
api/data.php            ← data-reading API (used by the front end)
assets/js/data-fallback.js   ← fallback when the API fails
assets/js/data.js       ← loads data into window.TT
admin/                  ← the entire admin panel
```

## Articles — Markdown

```
## Subheading

Normal paragraph

> Quote

- Item 1
- Item 2

![description](https://image-url.jpg)
```

## After saving

- Updates `data/site.json` immediately
- Updates `assets/js/data-fallback.js` automatically
- Refresh the web page (Ctrl+F5) to see the result

## Migrate new data from the old data.js

```bash
node tools/migrate-to-json.js
```

## Security

- Do not commit `data/admin-credentials.json`
- Change the default password before going live
- Use HTTPS in production
