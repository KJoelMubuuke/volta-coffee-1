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

## Architecture

```
volta-coffee-theme/
├── style.css           # Theme header + all styles (design tokens → components)
├── functions.php       # Theme setup, enqueue, CPTs, WooCommerce support, SEO hooks
├── header.php          # DOCTYPE, <head>, site header, skip link
├── footer.php          # Site footer, wp_footer()
├── front-page.php      # Homepage: hero, story, hours & location
├── page-menu.php       # Template for the cafe menu page (CPT-driven)
├── page.php            # Generic page template
├── index.php           # Blog fallback (required by WP)
├── app.js              # Menu filter tabs (ARIA-compliant) + scroll animations
├── inc/
│   └── menu.php        # menu_item CPT, menu_category taxonomy, price meta box
└── images/             # Static theme images (hero, about, menu, footer)
```

### Custom Post Types

**`menu_item`** — each entry is an admin-managed menu item.
- Taxonomy: `menu_category` (hierarchical, shown in REST API)
- Meta: `_volta_price` (integer, UGX; saved via nonce-verified meta box)
- Rendered by `page-menu.php` with a client-side category filter

**`coffee_origin`** — editorial content about coffee sourcing regions.
- Exposed via the WP REST API (`show_in_rest: true`)
- Supports title, editor, thumbnail, excerpt

### Performance

- All styles in a single `style.css` (no extra HTTP requests)
- JS loaded in footer with `true` as the last `wp_enqueue_script` argument
- Images include `loading="lazy"` where above-the-fold is not critical
- Google Fonts uses `font-display=swap` and is preceded by `preconnect` hints

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
