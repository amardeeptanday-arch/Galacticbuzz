<?php
/**
 * Any page without its own template (Privacy policy, Terms, new pages you add).
 */
get_header();
?>
<section class="page on">
  <div class="band phero" style="padding-block:clamp(56px,8vw,96px) clamp(40px,6vw,64px)">
    <div class="hx-grid"></div>
    <div class="wrap" style="position:relative">
      <div class="crumbs"><a href="<?php echo gb_url(); ?>">Home</a><span>/</span><span><?php the_title(); ?></span></div>
      <h1 style="font-size:clamp(2.2rem,4.5vw,3.4rem)"><?php the_title(); ?></h1>
    </div>
  </div>
  <div class="sec" style="padding-top:clamp(32px,5vw,56px)">
    <div class="wrap">
      <article class="gb-prose">
        <?php
        while ( have_posts() ) :
          the_post();
          the_content();
        endwhile;
        ?>
      </article>
    </div>
  </div>
</section>
<?php
get_footer();
