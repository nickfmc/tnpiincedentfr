<?php
/*
Template Name: Home Page
*/
?>

<?php get_header(); ?>

<div class="o-layout-row">
  <main class="" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/WebPageElement">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <section class="editor-content  clearfix">
        <div class="alignfull">
          <div class="o-wrapper-wide">
            <div class="c-home-intro">
            <?php if( get_field('incident_title', 'options') ) { echo '<h1 class="c-incident-title">' . get_field('incident_title', 'options') . '</h1>'; }?>
            </div>
            <div class="c-incident-body">
              <?php if( get_field('incident_summary', 'options') ) { echo '<p class="has-large-font-size">' . get_field('incident_summary', 'options') . '</p>'; }?>
          
              <h2 class="h4-style is-style-u-eyebrow-heading">Current Status</h2>
            <?php
            if (have_rows('updates', 'options')):
                $updates = array();
                while (have_rows('updates', 'options')):
            the_row();
            $updates[] = array(
                'update_title' => get_sub_field('update_title', 'options'),
                'time_stamp' => get_sub_field('time_stamp', 'options'),
                'update' => get_sub_field('update', 'options'),
                'gallery' => get_sub_field('gallery', 'options')
            );
                endwhile;
          
                // Get the total number of updates
                $total_updates = count($updates);
          
                // Get only the last update
                $last_update = end($updates);
          
                if ($last_update):
            ?>
            <div>
                <!-- <h2 class="h4-style">Update #<?php //echo $total_updates; ?></h2> -->
                <?php echo $last_update['update']; ?>
            </div>
                <?php
                endif;
            endif;
            ?>
          
            <div class="c-btn-primary"><a href="/updates">View Updates</a></div>
          
          
          
            <?php if( get_field('key_actions_taken', 'options') ) { echo '<h2 class="mt-14 h4-style is-style-u-eyebrow-heading">Key Actions Taken</h2>'; }?>
          
            <?php if( have_rows('key_actions_taken', 'options') ): ?>
              <ul>
             <?php while( have_rows('key_actions_taken', 'options') ): the_row(); ?>
          
            <li>
              <?php echo get_sub_field('action', 'options'); ?>
              <?php if( have_rows('sub_actions', 'options') ): ?>
                <ul>
               <?php while( have_rows('sub_actions', 'options') ): the_row(); ?>
          
              <li><?php echo get_sub_field('subaction', 'options'); ?></li>
          
              <?php endwhile; ?>
              </ul>
              <?php endif; ?>
            </li>
          
            <?php endwhile; ?>
            </ul>
            <?php endif; ?>
            </div>
          </div>
          
        </div>
      </section> 
      <!-- /editor-content -->
    <?php endwhile; endif; // END main loop (if/while) ?>
  </main>
  <!-- /container -->
</div>
<!-- /layout-row-->

<?php // IF USING PARTS -> get_template_part( 'template-part/name-of-part' ); ?>

<?php get_footer(); ?>
