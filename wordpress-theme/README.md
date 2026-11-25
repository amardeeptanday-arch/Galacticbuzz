# Galacticbuzz WordPress theme

`galacticbuzz.zip` is the installable theme for galacticbuzz.io. It reproduces the approved prototype (`website/index.html`, v0.7) as a WordPress theme.

## Install

1. In WordPress: **Appearance → Themes → Add New Theme → Upload Theme**, choose `galacticbuzz.zip`, **Install Now**, then **Activate**.
2. Activation creates the pages (Home, Services, Who we serve, About, Contact, Find a partner, Privacy policy, Terms), sets Home as the front page, switches on clean URLs and adds the founder to **Team**. Running it again never duplicates anything.

## Editing

| What | Where |
| --- | --- |
| Email, phone, LinkedIn, booking link (Calendly or Cal.com), form recipient, hours, reply time | Appearance → Customize → Galacticbuzz → Contact & booking |
| Testimonials (homepage section appears once one is filled in) | Appearance → Customize → Galacticbuzz → Testimonials |
| Team members: name, photo, role, bio, time zone, LinkedIn | **Team** in the dashboard menu |
| Form submissions | **Leads** in the dashboard menu (also emailed) |
| Privacy policy, Terms | Pages |
| Industries list | `inc/content.php` → `gb_verticals()` |
| Page copy and layout | `front-page.php`, `page-*.php` |

## Rebuilding from the prototype

`python3 wordpress-theme/build_theme.py` regenerates `assets/css/site.css`, `template-parts/sprite.php`, `header.php`, `footer.php` and the page templates from `website/index.html`. Hand-written files (`functions.php`, `inc/*`, `assets/js/site.js`, `page.php`, `index.php`, `404.php`) are not touched. Zip the `galacticbuzz` folder afterwards.
