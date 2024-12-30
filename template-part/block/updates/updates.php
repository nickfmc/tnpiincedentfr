<?php

/**
 * blockname Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'c-updates-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'c-updates';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
if( $is_preview ) {
    $className .= ' is-admin';
}

?>


 
<div id="<?php echo esc_attr($id); ?>" class="alignfull <?php echo esc_attr($className); ?>"  >


    <?php if( have_rows('updates', 'options') ): ?>
        <?php
        $updates = array();
        while( have_rows('updates', 'options') ): the_row();
            $updates[] = array(
                'update_title' => get_sub_field('update_title', 'options'),
                'time_stamp' => get_sub_field('time_stamp', 'options'),
                'update' => get_sub_field('update', 'options'),
                'gallery' => get_sub_field('gallery', 'options')
            );
        endwhile;
        $updates = array_reverse($updates);
    
        // Get the total number of updates
        $total_updates = count($updates);
        ?>
    
        <?php foreach( $updates as $index => $update ): ?>
            <?php $count = $total_updates - $index; ?>
            <div>
            <div class="o-wrapper-wide">
                <h2 class="h4-style">Mise à jour #<?php echo $count; ?></h2>
               <span class="c-updates-timestamp">
                   <?php 
                   $formatter = new IntlDateFormatter(
                       'fr_FR',
                       IntlDateFormatter::LONG,
                       IntlDateFormatter::SHORT,
                       'America/New_York'  // EST timezone
                   );
                   echo $formatter->format(strtotime($update['time_stamp'])); ?> EST
               </span>
               
                <?php echo $update['update']; ?>
    
                <?php if( $update['gallery'] ): ?>
                <div class="gallery">
                    <?php foreach( $update['gallery'] as $image ): ?>
                        <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


</div>
