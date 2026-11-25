<?php
/**
 * Fallback template (blog posts and archives, if you ever publish them).
 */
get_header();
?>
<section class="page on">
  <div class="band phero" style="padding-block:clamp(56px,8vw,96px) clamp(40px,6vw,64px)">
    <div class="hx-grid"></div>
    <div class="wrap" style="position:relative">
      <h1 style="font-size:clamp(2.2rem,4.5vw,3.4rem)"><?php echo is_singular() ? esc_html( get_the_title() ) : esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
    </div>
  </div>
  <div class="sec" style="padding-top:clamp(32px,5vw,56px)">
    <div class="wrap">
      <?php if ( have_posts() ) : ?>
        <?php if ( is_singular() ) : ?>
          <article class="gb-prose"><?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?></article>
        <?php else : ?>
          <div class="grid g3">
            <?php while ( have_posts() ) : the_post(); ?>
              <a class="card spot" href="<?php the_permalink(); ?>" style="text-decoration:none;display:grid;gap:8px">
                <span class="xs mono muted"><?php echo esc_html( get_the_date() ); ?></span>
                <h3><?php the_title(); ?></h3>
                <p class="small muted"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
              </a>
            <?php endwhile; ?>
          </div>
          <div style="margin-top:28px"><?php the_posts_pagination(); ?></div>
        <?php endif; ?>
      <?php else : ?>
        <p class="muted">Nothing here yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php
get_footer();
