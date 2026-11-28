# Galacticbuzz website: editing guide

This is everything needed to edit and redeploy **galacticbuzz.io**. The site is a custom WordPress theme. It doesn't use a page builder (no Elementor, Divi or similar), so changes are made in the theme files and then the theme is re-uploaded.

## What's in this package

```
galacticbuzz-source/
├── EDITING-GUIDE.md            ← this file
├── galacticbuzz.zip            ← current version, ready to upload as-is (v1.0.0)
├── theme/galacticbuzz/         ← THE LIVE SITE. Edit these files.
│   ├── style.css               theme header (name, version)
│   ├── functions.php           setup, fonts and scripts, helper functions
│   ├── header.php              top bar and menu
│   ├── footer.php              footer, mobile "Book a call" bar, profile pop-up
│   ├── front-page.php          Home
│   ├── page-services.php       /services/
│   ├── page-who-we-serve.php   /who-we-serve/
│   ├── page-about.php          /about/
│   ├── page-contact.php        /contact/
│   ├── page-find-a-partner.php /find-a-partner/
│   ├── page.php                any other page (Privacy policy, Terms, new pages)
│   ├── index.php, 404.php      fallback and "not found" pages
│   ├── inc/content.php         industries list, ribbon, testimonials section
│   ├── inc/team.php            "Team" section in the dashboard and the team cards
│   ├── inc/leads.php           form handling: saves under "Leads" and sends an email
│   ├── inc/customizer.php      Appearance → Customize → Galacticbuzz settings
│   ├── inc/setup.php           runs once on activation: creates pages, sets the homepage
│   ├── assets/css/site.css     all styling (design tokens at the top)
│   ├── assets/js/site.js       all interactions (animations, forms, team pop-up)
│   ├── template-parts/sprite.php  SVG icon set (<use href="#i-name">)
│   └── screenshot.png          thumbnail shown in Appearance → Themes
├── design-reference/index.html ← the approved prototype (open it in a browser). Reference only.
└── tools/build_theme.py        ← one-time converter from the prototype. See the warning below.
```

## Content that doesn't need code

Change these in WordPress itself:

| What | Where in WordPress |
| --- | --- |
| Email, phone, LinkedIn, Calendly link, where form emails go, hours, reply-time line | Appearance → Customize → Galacticbuzz → Contact & booking |
| Testimonials (the homepage section appears once one quote is filled in) | Appearance → Customize → Galacticbuzz → Testimonials |
| Team members (photo = Featured image, bio = main text, role, time zone, LinkedIn, "Ask me about") | **Team** in the dashboard menu |
| Form submissions | **Leads** in the dashboard menu |
| Privacy policy, Terms | Pages |

## Code edits: where things live

- **Headlines, paragraphs, buttons:** in the page template, e.g. the homepage hero is near the top of `front-page.php`. It's plain HTML with small `<?php ... ?>` bits.
- **Industries list** (Who we serve cards, homepage tiles and ribbon): the `gb_verticals()` array in `inc/content.php`.
- **Markets orbit** (US, UK, UAE, Australia on Who we serve): the `REG` array in `assets/js/site.js`.
- **Menu items:** the `foreach` array in `header.php`.
- **Footer links:** `footer.php`.
- **Colours, fonts, spacing:** the CSS custom properties on `:root` at the top of `assets/css/site.css`. The dark theme values are in the block marked `v0.7: dark-first premium SaaS`, near the end of the file. Fonts are Geist and Geist Mono, loaded in `functions.php`.
- **Icons:** add an `<symbol id="i-yourname">` to `template-parts/sprite.php`, then use `<svg><use href="#i-yourname"/></svg>`.
- **Links between pages:** use `<?php echo gb_url( 'contact', 'book' ); ?>`, which outputs `/contact/#book`. Don't hard-code the domain.
- **Team cards, pop-up and cursor effects:** markup in `inc/team.php` (`gb_team_cards()`), behaviour in `site.js`, block "Team".

## Rules that keep the site working

1. **Edit the files in `theme/galacticbuzz/` directly.** Do **not** run `tools/build_theme.py`. It regenerates the page templates, `header.php`, `footer.php` and `site.css` from the prototype and would overwrite every later edit. It was only used once to make the first version.
2. **Keep these IDs and classes the scripts rely on:** `#callForm`, `#msgForm`, `#matchWiz`, `#callDone`, `#msgDone`, `#briefDone`, the `<p class="err" hidden></p>` inside each form, the hidden `gb_hp` spam-trap field, `#feed`, `#tilt`, `#tiltWrap`, `#phases`, `#phHl`, `#orbit`, `#regionCard`, `#teamGrid`, `#cursorDot`, `#profileModal`, `#profileBody`, `#toast`, `#menuBtn`, `#mnav`.
3. **Form fields** are collected automatically by their `<label>` text. To add a field, add a normal `<label for="x">` and `<input id="x">` inside the form; it then appears in Leads and in the email. Add `data-req` to make it required.
4. **Escape output** in PHP: `esc_html()`, `esc_attr()`, `esc_url()`.
5. **Honesty:** don't add made-up testimonials, client logos or results. The product screenshots carry an "Example" label because their numbers are illustrations; keep that label if you change them.
6. Check `php -l file.php` on any PHP file you changed before uploading.

## Publishing an update

1. Bump `Version:` in `style.css` and `GB_VERSION` in `functions.php` (e.g. 1.0.1), so browsers load the new CSS and JS.
2. Zip the **`galacticbuzz` folder itself**, so the zip contains `galacticbuzz/style.css` and not loose files.
3. In WordPress: **Appearance → Themes → Add New Theme → Upload Theme**, choose the zip, then **Install Now** → **Replace active with uploaded**.
4. If Hostinger caching (LiteSpeed) still shows the old version, purge the cache from the WordPress admin bar or hPanel.

Re-uploading never duplicates pages: the activation setup skips anything that already exists.

## Testing locally (optional)

Any local WordPress works, for example LocalWP or `wp-env`. Copy `theme/galacticbuzz` into `wp-content/themes/` and activate it. The theme needs WordPress 6.0+ and PHP 7.4+.
