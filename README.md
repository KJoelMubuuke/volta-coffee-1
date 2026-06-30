# Volta Coffee Theme

Custom WordPress theme for Volta Coffee — a single-location craft coffee shop. Built from scratch to demonstrate WordPress development skills for a professional portfolio.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- WooCommerce 8.0+ (for shop & ordering pages)
- [Volta Loyalty Points](../plugins/volta-loyalty-points) plugin (for the Rewards widget)

## Features

| Feature | Skills demonstrated |
|---|---|
| Custom post type: `menu_item` (admin-managed menu) | CPTs, taxonomies, meta boxes, admin columns |
| Custom post type: `coffee_origin` | REST API exposure via `show_in_rest` |
| `menu_category` taxonomy + price meta box | Admin-panel content management |
| WooCommerce full integration | Theme support, template hooks, sidebar removal |
| Schema.org JSON-LD (`CafeOrCoffeeShop`) | SEO, structured data |
| Dynamic per-page `<meta name="description">` | SEO best practice |
| Accessible skip link, ARIA landmark roles | WCAG 2.1 AA compliance |
| Menu filter tab list with arrow-key navigation | WCAG 2.1 §4.2.7 keyboard interaction |
| `prefers-reduced-motion` CSS block | Motion accessibility |
| `:focus-visible` high-contrast outlines | Keyboard-user accessibility |
| Responsive layout (CSS Grid + media queries) | Cross-device compatibility |
| Google Fonts loaded via `wp_enqueue_style` | WP asset management best practice |
| `font-display: swap` + `preconnect` hints | Core Web Vitals / performance |
| IntersectionObserver scroll animation | Progressive enhancement |
| Single source of truth for prices (linked WooCommerce product, with manual fallback) | Data integrity across CPT + WooCommerce |
| Menu data fetched in one query + cached via transient, invalidated on edit | Query optimization, caching strategy |
| Real fallback template for blog/search/archive/404 (instead of a stub) | Template hierarchy completeness |

## Architecture

```
volta-coffee-theme/
├── style.css           # Theme header + all styles (design tokens → components)
├── functions.php       # Theme setup, enqueue, CPTs, WooCommerce support, SEO hooks
├── header.php          # DOCTYPE, <head>, site header, skip link
├── footer.php          # Site footer, wp_footer()
├── front-page.php      # Homepage: hero, story, hours & location
├── page-menu.php       # Template for the cafe menu page (CPT-driven, single cached query)
├── page.php            # Generic page template
├── index.php           # Fallback: blog index, search results, archives, 404s
├── app.js              # Menu filter tabs (ARIA-compliant) + scroll animations
├── inc/
│   └── menu.php        # menu_item CPT, menu_category taxonomy, price meta box, volta_get_menu_data()
└── images/             # Static theme images (hero, about, menu, footer)
```

### Custom Post Types

**`menu_item`** — each entry is an admin-managed menu item.
- Taxonomy: `menu_category` (hierarchical, shown in REST API)
- Meta: `_volta_price` (integer, UGX; saved via nonce-verified meta box) — used when no shop product is linked
- Meta: `_volta_linked_product_id` — optional WooCommerce product ID; when set, its live price overrides `_volta_price` so the Menu page and Shop page can never disagree
- Rendered by `page-menu.php` via `volta_get_menu_data()`, a single `WP_Query` across all menu items grouped by category and cached in a transient (`volta_menu_items_v1`, 1 hour), invalidated on menu item save/trash/delete, category edit, and linked-product save

**`coffee_origin`** — editorial content about coffee sourcing regions.
- Exposed via the WP REST API (`show_in_rest: true`)
- Supports title, editor, thumbnail, excerpt

### Fallback Template (`index.php`)

WordPress falls back to `index.php` for anything without a dedicated template — the blog index, search results, the `coffee_origin` archive, and (in the absence of a `404.php`) not-found requests. It renders a real post grid with pagination, a search-aware/404-aware page-hero heading, and a search form on empty search results, instead of a placeholder message.

### Performance

- All styles in a single `style.css` (no extra HTTP requests)
- JS loaded in footer with `true` as the last `wp_enqueue_script` argument
- Images include `loading="lazy"` where above-the-fold is not critical
- Google Fonts uses `font-display=swap` and is preceded by `preconnect` hints
- Menu page data is fetched once per cache window instead of once per category per request

### Accessibility

Meets WCAG 2.1 Level AA for all theme-controlled content:
- Skip-to-content link (visible on focus)
- Semantic HTML5 landmarks (`<header>`, `<main>`, `<footer>`, `<nav>`, `<address>`)
- ARIA roles + labels on nav and interactive widgets
- Roving `tabindex` + arrow-key navigation on menu filter tab list
- `prefers-reduced-motion` disables all CSS transitions and animations
- High-contrast `:focus-visible` outlines on every interactive element

## Installation

1. Copy `volta-coffee-theme/` into `wp-content/themes/`.
2. Activate the theme in **Appearance → Themes**.
3. Install and activate WooCommerce.
4. Install and activate the Volta Loyalty Points plugin.
5. Create a page with the slug `menu` and assign the **Menu Page** template.
6. Add menu items via **Cafe Menu → Add New** in wp-admin.

## Local Development

This project uses [Local by Flywheel](https://localwp.com/) (site: `volta-coffee.local`).

```bash
# Open the site root
cd "Local Sites/volta-coffee/app/public"
```

No build step required — the theme uses vanilla JS and plain CSS.
