#!/usr/bin/env python3
"""Build the Galacticbuzz WordPress theme templates from the approved
prototype (website/index.html).

Hand-written theme files (functions.php, inc/*, assets/js/site.js) are left
alone; this script regenerates assets/css/site.css, template-parts/sprite.php
and the page templates.

Usage: python3 wordpress-theme/build_theme.py
"""
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(ROOT, 'website', 'index.html')
OUT = os.path.join(ROOT, 'wordpress-theme', 'galacticbuzz')

html = open(SRC, encoding='utf-8').read()

# ---------------------------------------------------------------- helpers
LINKS = {
    'home': ('', ''), 'services': ('services', ''), 'svc-li': ('services', 'svc-li'),
    'svc-email': ('services', 'svc-email'), 'svc-call': ('services', 'svc-call'),
    'svc-brand': ('services', 'svc-brand'), 'industries': ('who-we-serve', ''),
    'how': ('', 'how'), 'about': ('about', ''), 'team': ('about', 'team'),
    'contact': ('contact', ''), 'book': ('contact', 'book'), 'message': ('contact', 'message'),
    'find': ('find-a-partner', ''), 'brief': ('find-a-partner', 'brief'),
    'legal': ('privacy-policy', ''), 'terms': ('terms', ''),
}


def php_url(key):
    slug, anchor = LINKS[key]
    if anchor:
        return "<?php echo gb_url( '%s', '%s' ); ?>" % (slug, anchor)
    return "<?php echo gb_url( '%s' ); ?>" % slug


def links(s):
    def rep(m):
        key = m.group(1)
        if key not in LINKS:
            raise SystemExit('Unknown in-page link #%s' % key)
        return 'href="%s"' % php_url(key)
    return re.sub(r'href="#(?!i-)([a-z0-9-]+)"', rep, s)


def clean(s):
    s = re.sub(r' data-note="[^"]*"', '', s)
    s = s.replace(' data-sticky', '')
    s = s.replace('<span class="ph">sample</span>', '<span class="ex">Example</span>')
    s = s.replace('<span class="ph">illustrative</span>', '<span class="ex">Illustrative</span>')
    s = re.sub(r'\s*<span class="ph"[^>]*>[^<]*</span>', '', s)
    s = s.replace('connections this quarter · sample', 'connections this quarter · example')
    s = re.sub(r'<p class="(?:note-line|xs muted)"[^>]*>\s*</p>', '', s)
    s = s.replace('<section class="page" id=', '<section class="page on" id=')
    return links(s)


def section(page_id):
    start = html.index('<section class="page" id="%s"' % page_id)
    end = html.index('</section>', start) + len('</section>')
    return html[start:end]


def block_start(s, note_prefix):
    """Index of the tag that carries data-note="<note_prefix>..."."""
    i = s.index('data-note="%s' % note_prefix)
    return s.rindex('<', 0, i)


def cut(s, start_note, end_note):
    a = block_start(s, start_note)
    b = block_start(s, end_note)
    return s[:a], s[a:b], s[b:]


def must_replace(s, old, new, count=1):
    if s.count(old) < count:
        raise SystemExit('Pattern not found:\n' + old[:160])
    return s.replace(old, new)


def write(rel, content):
    path = os.path.join(OUT, rel)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
    print('wrote', rel, len(content))


HEAD = "<?php\n/**\n * %s\n * Generated from the approved prototype by wordpress-theme/build_theme.py.\n */\nget_header();\n?>\n"
FOOT = "\n<?php get_footer();\n"

# ---------------------------------------------------------------- CSS
styles = re.findall(r'<style>(.*?)</style>', html[:html.index('<svg width="0"')], re.S)
extra = r'''
/* ===== WordPress build ===== */
[hidden]{display:none!important}
body{margin:0}
img{max-width:100%;height:auto}
.page{display:block}
.ex{margin-left:auto;font-family:var(--f-mono);font-size:.6rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);border:1px solid var(--line);border-radius:6px;padding:2px 7px;white-space:nowrap}
.legend .ex{margin-left:0}
.hp{position:absolute!important;left:-9999px!important;width:1px;height:1px;overflow:hidden}
.tcard .photo img{width:100%;height:100%;object-fit:cover;display:block}
.book-embed{padding:0;overflow:hidden;margin-top:28px;border-radius:var(--r-lg)}
.book-embed iframe{width:100%;height:700px;border:0;display:block;background:#fff}
.skip-link{position:absolute;left:-9999px;top:8px;z-index:200;background:#F2F2FF;color:#0A0B1E;padding:10px 14px;border-radius:8px;font-weight:600}
.skip-link:focus{left:16px}
.gb-prose{max-width:72ch;display:grid;gap:16px;color:var(--muted);font-size:1.02rem;line-height:1.7}
.gb-prose h2,.gb-prose h3{color:var(--ink);margin-top:18px;font-size:1.4rem}
.gb-prose a{color:var(--band-accent)}
.gb-prose ul,.gb-prose ol{padding-left:20px;display:grid;gap:6px}
.gb-prose img{border-radius:12px}
.not-found{min-height:60vh;display:grid;place-content:center;text-align:center;gap:16px}
'''
write('assets/css/site.css', '\n'.join(s.strip() for s in styles) + '\n' + extra)

# ---------------------------------------------------------------- sprite
sprite = html[html.index('<svg width="0"'):html.index('<div class="proto-bar">')].strip()
write('template-parts/sprite.php', sprite + '\n')

# ---------------------------------------------------------------- home
home = section('p-home')
pre, _testimonials, post = cut(home, 'HOME 8', 'HOME 9')
home = pre + '<?php gb_testimonials(); ?>\n\n  ' + post
home = must_replace(home, '<div class="marq-track" id="marq"></div>', '<div class="marq-track" id="marq"><?php gb_marquee(); ?></div>')
home = must_replace(home, '<div class="vtiles" id="homeVerticals"></div>', '<div class="vtiles" id="homeVerticals"><?php gb_vertical_tiles(); ?></div>')
write('front-page.php', HEAD % 'Homepage.' + clean(home) + FOOT)

# ---------------------------------------------------------------- services
write('page-services.php', HEAD % 'Services page (slug: services).' + clean(section('p-services')) + FOOT)

# ---------------------------------------------------------------- who we serve
ind = section('p-industries')
ind = must_replace(ind, '<div class="ind-grid" id="indGrid"></div>', '<div class="ind-grid" id="indGrid"><?php gb_industry_cards(); ?></div>')
ind = must_replace(ind, '<div class="orbit" id="orbit">', '<div class="orbit" id="orbit" data-book="<?php echo gb_url( \'contact\', \'book\' ); ?>">')
write('page-who-we-serve.php', HEAD % 'Who we serve page (slug: who-we-serve).' + clean(ind) + FOOT)

# ---------------------------------------------------------------- about
about = section('p-about')
pre, _offices, post = cut(about, 'ABOUT 5', 'ABOUT 6')
about = pre + post
team_start = block_start(about, 'ABOUT 3')
team_end = block_start(about, 'ABOUT 4')
team = about[team_start:team_end]
team = must_replace(team, '<div class="team-grid" id="teamGrid"></div>', '<div class="team-grid" id="teamGrid" data-book="<?php echo gb_url( \'contact\', \'book\' ); ?>"><?php echo $gb_team; // phpcs:ignore -- escaped in gb_team_cards(). ?></div>')
team = team.replace('across four time zones.', 'across time zones.')
about = about[:team_start] + '<?php if ( $gb_team ) : ?>\n  ' + team + '<?php endif; ?>\n\n  ' + about[team_end:]
write('page-about.php', HEAD % 'About page (slug: about).' + "<?php $gb_team = gb_team_cards(); ?>\n" + clean(about) + FOOT)

# ---------------------------------------------------------------- contact
contact = section('p-contact')
contact = must_replace(contact, '<p class="lead">Pick what fits. We reply within one working day. <span class="ph">confirm</span></p>',
                       '<p class="lead">Pick what fits. <?php echo esc_html( gb_mod( \'reply_time\' ) ); ?></p>')
slot_start = contact.index('<div class="card" style="margin-top:28px;display:grid;gap:16px">')
slot_end = contact.index('<div class="slots" id="cSlots"></div>', slot_start)
slot_end = contact.index('</div>', slot_end + len('<div class="slots" id="cSlots"></div>')) + len('</div>')
slot_card = contact[slot_start:slot_end]
slot_card = slot_card.replace('<b>Pick a time</b><span class="ph">Calendly embed</span>', '<b>Pick a preferred time</b><span class="xs muted">We confirm by email</span>')
booking = ("<?php $gb_booking = gb_mod( 'booking_url' ); if ( $gb_booking ) : ?>\n"
           "        <div class=\"card book-embed\"><iframe src=\"<?php echo esc_url( $gb_booking ); ?>\" title=\"Book a call\" loading=\"lazy\"></iframe></div>\n"
           "        <?php else : ?>\n        " + slot_card + "\n        <?php endif; ?>")
contact = contact[:slot_start] + booking + contact[slot_end:]
contact = must_replace(contact, '<form class="card raised" id="callForm" style="display:grid;gap:16px" novalidate>',
                       '<form class="card raised" id="callForm" style="display:grid;gap:16px" novalidate><input type="text" name="gb_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">')
contact = must_replace(contact, '<button class="btn btn-primary btn-lg" type="submit">Book my call',
                       '<button class="btn btn-primary btn-lg" type="submit"><?php echo $gb_booking ? \'Send details\' : \'Request my call\'; ?>')
contact = must_replace(contact, '<svg width="14" height="14"><use href="#i-check"/></svg>Call booked</span>\n          <h3 style="font-size:1.8rem">See you <span id="callSlot">soon</span>.</h3>\n          <p class="muted">A calendar invite is on its way. If you can, send us your LinkedIn profile and your three best clients before the call.</p>',
                       '<svg width="14" height="14"><use href="#i-check"/></svg>Request sent</span>\n          <h3 style="font-size:1.8rem">Thanks. We’ll be in touch.</h3>\n          <p class="muted"><?php if ( $gb_booking ) : ?>If you haven’t picked a time in the calendar yet, do that now.<?php else : ?>We’ll confirm <span id="callSlot">a time</span> by email.<?php endif; ?> If you can, send us your LinkedIn profile and your three best clients before the call.</p>')
contact = must_replace(contact, '<form class="card raised" id="msgForm" style="display:grid;gap:16px" novalidate>',
                       '<form class="card raised" id="msgForm" style="display:grid;gap:16px" novalidate><input type="text" name="gb_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">')
contact = must_replace(contact, '<h3 style="font-size:1.6rem">Thanks. We’ll reply within one working day.</h3>',
                       '<h3 style="font-size:1.6rem">Thanks. <?php echo esc_html( gb_mod( \'reply_time\' ) ); ?></h3>')
cd_start = contact.index('<ul class="clean cdl">')
cd_end = contact.index('</div>\n    </div>\n  </div>\n</section>')
details = '''<ul class="clean cdl">
          <?php $gb_email = gb_mod( 'contact_email' ); if ( $gb_email ) : ?>
          <li><span class="mono xs muted">EMAIL</span><div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap"><a class="mono" style="font-weight:600;text-decoration:none" href="mailto:<?php echo esc_attr( antispambot( $gb_email ) ); ?>"><?php echo esc_html( antispambot( $gb_email ) ); ?></a><button class="btn btn-ghost btn-sm" type="button" id="copyEmail" data-email="<?php echo esc_attr( $gb_email ); ?>">Copy</button></div></li>
          <?php endif; ?>
          <?php $gb_phone = gb_mod( 'phone' ); if ( $gb_phone ) : ?>
          <li><span class="mono xs muted">PHONE / WHATSAPP</span><a class="mono" style="font-weight:600;text-decoration:none" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $gb_phone ) ); ?>"><?php echo esc_html( $gb_phone ); ?></a></li>
          <?php endif; ?>
          <?php $gb_li = gb_mod( 'linkedin' ); if ( $gb_li ) : ?>
          <li><span class="mono xs muted">LINKEDIN</span><a class="link-arrow" href="<?php echo esc_url( $gb_li ); ?>" target="_blank" rel="noopener">Galacticbuzz <svg width="14" height="14"><use href="#i-arrow"/></svg></a></li>
          <?php endif; ?>
          <li><span class="mono xs muted">HOURS</span><b><?php echo esc_html( gb_mod( 'hours' ) ); ?></b></li>
        </ul>
      '''
contact = contact[:cd_start] + details + contact[cd_end:]
write('page-contact.php', HEAD % 'Contact page (slug: contact).' + clean(contact) + FOOT)

# ---------------------------------------------------------------- find a partner (+ brief)
find = section('p-find')
pre, faq_and_cta = find[:block_start(find, 'FIND 5')], find[block_start(find, 'FIND 5'):]
faq = faq_and_cta[:block_start(faq_and_cta, 'FIND 6')]
brief = section('p-brief')
brief_inner = brief[brief.index('>') + 1:brief.rindex('</section>')]
brief_inner = must_replace(brief_inner, '<div class="sec"', '<div class="sec" id="brief"')
brief_inner = re.sub(r'<a class="link-arrow small" href="#find"[^>]*>.*?</a>', '', brief_inner, flags=re.S)
brief_inner = must_replace(brief_inner, '<h1 style="font-size:clamp(2rem,4vw,2.8rem)">Start your brief.</h1>', '<h2>Start your brief.</h2>')
brief_inner = must_replace(brief_inner, '<form class="wiz" id="matchWiz" novalidate>', '<form class="wiz" id="matchWiz" novalidate><input type="text" name="gb_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">')
find = pre + brief_inner + '\n  ' + faq + '</section>'
find = find.replace('<p>A person on our team picks firms that fit, usually within two working days. <span class="ph">confirm</span></p>', '<p>A person on our team picks firms that fit and sends you the shortlist.</p>')
write('page-find-a-partner.php', HEAD % 'Find a partner page (slug: find-a-partner).' + clean(find) + FOOT)

# ---------------------------------------------------------------- header
proto_end = html.index('<header class="topbar">')
header_html = html[proto_end:html.index('</header>') + len('</header>')]
nav_old = header_html[header_html.index('<nav class="nav"'):header_html.index('</nav>') + 6]
nav_new = '''<nav class="nav" aria-label="Main">
      <?php foreach ( array( 'services' => 'Services', 'who-we-serve' => 'Who we serve', 'about' => 'About', 'contact' => 'Contact' ) as $gb_slug => $gb_label ) : ?>
      <a href="<?php echo gb_url( $gb_slug ); ?>"<?php echo gb_current() === $gb_slug ? ' class="on" aria-current="page"' : ''; ?>><?php echo esc_html( $gb_label ); ?></a>
      <?php if ( 'who-we-serve' === $gb_slug ) : ?><a href="<?php echo gb_url( '', 'how' ); ?>">How it works</a><?php endif; ?>
      <?php endforeach; ?>
    </nav>'''
header_html = header_html.replace(nav_old, nav_new)
header_html = clean(header_html)
header = '''<?php
/**
 * Site header. Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#06071A">
<?php if ( is_front_page() ) : ?><meta name="description" content="Galacticbuzz is a virtual BD team for B2B service firms. LinkedIn outreach, cold email, calling and branding, run from your own profile. You get the meetings; the relationships stay yours."><?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<?php get_template_part( 'template-parts/sprite' ); ?>
''' + header_html + '''

<main id="main">
'''
write('header.php', header)

# ---------------------------------------------------------------- footer
footer_html = html[html.index('<footer'):html.index('</footer>') + len('</footer>')]
footer_html = footer_html.replace('<li><a href="#legal">Terms</a></li>', '<li><a href="#terms">Terms</a></li>')
footer_html = footer_html.replace('<li><a href="https://www.linkedin.com" target="_blank" rel="noopener">LinkedIn</a></li>',
                                  "<?php if ( gb_mod( 'linkedin' ) ) : ?><li><a href=\"<?php echo esc_url( gb_mod( 'linkedin' ) ); ?>\" target=\"_blank\" rel=\"noopener\">LinkedIn</a></li><?php endif; ?>")
footer_html = re.sub(r'<div class="foot-bottom">.*?</div>',
                     '<div class="foot-bottom"><span>© <?php echo esc_html( gmdate( \'Y\' ) ); ?> Galacticbuzz</span><span class="mono xs">galacticbuzz.io</span></div>',
                     footer_html, flags=re.S)
footer_html = clean(footer_html)
sticky = '<?php if ( gb_current() !== \'contact\' ) : ?><div class="m-sticky"><a class="btn btn-primary" href="<?php echo gb_url( \'contact\', \'book\' ); ?>">Book a call</a></div><?php endif; ?>'
profile = html[html.index('<div class="modal" id="profileModal"'):html.index('<div class="notes-fab">')].strip()
footer = '''<?php
/**
 * Site footer. Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
?>
</main>

''' + footer_html + '''

''' + sticky + '''

''' + profile + '''
<div class="toast" id="toast" hidden role="status"></div>
<?php wp_footer(); ?>
</body>
</html>
'''
write('footer.php', footer)

print('done')
