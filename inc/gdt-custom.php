<?php
/**
 * Custom functions for this project? If yes, drop them here!
 */

  // If using acf icon picker - https://github.com/houke/acf-icon-picker -  modify the path to the icons directory
//   add_filter( 'acf_icon_path_suffix', 'acf_icon_path_suffix' );

//   function acf_icon_path_suffix( $path_suffix ) {
//       return 'img/icons/';
//   }
  
//used for Stackable blocks support - match to wrapper width 
global $content_width;
$content_width = 1180;


// generateblocks PRO breakpoints
add_action( 'wp', function() {
  add_filter( 'generateblocks_media_query', function( $query ) {
      $query['desktop'] = '(min-width: 1140px)';
      $query['tablet'] = '(max-width: 1139px)';
      $query['tablet_only'] = '(max-width: 1139px) and (min-width: 767px)';
      $query['mobile'] = '(max-width: 767px)';

      return $query;
  } );
}, 20 );



// Yoast Breadcrumbs Accessibility fix
// Convert the Yoast Breadcrumbs output wrapper into an ordered list.
add_filter( 'wpseo_breadcrumb_output_wrapper', function() {
	return 'ol';
} );

// Convert the Yoast Breadcrumbs single items into list items.
add_filter( 'wpseo_breadcrumb_single_link_wrapper', function() {
	return 'li';
} );

add_filter( 'wpseo_breadcrumb_separator', function() {
    return '<li class="breadcrumb-seperator" aria-hidden="true"> <span>/</span>/ </li>';
} );




class Custom_Menu_Walker extends Walker_Nav_Menu {
    // Start Level
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (!empty($args->has_children)) {
            $classes[] = 'menu-item-has-children';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        // Render top-level items differently
        if ($depth == 0) {
            $output .= $indent . '<li' . $id . $class_names .'>';
            $output .= '<button aria-haspopup="true" aria-expanded="false"><span>' . apply_filters('the_title', $item->title, $item->ID) . '</span></button>';
            return;
        }

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        if (!empty($args->has_children)) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // Check if item has children
    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        if (!$element)
            return;

        $id_field = $this->db_fields['id'];

        // Display this element
        if (is_array($args[0]))
            $args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
        else if (is_object($args[0]))
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}























add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {
    // Add this line to test if the function is running
    error_log('ACF Options Page Init Running');
    
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' 	=> 'Updates',
            'menu_title'	=> 'Updates',
            'menu_slug' 	=> 'updates',
            'capability'	=> 'manage_options', // Changed to admin capability for testing
            'redirect'		=> false
        ));
    }
}



// Create incident user role
function create_custom_acf_editor_role() {
    add_role(
        'acf_options_editor',
        'ACF Options Editor',
        array(
            'read' => true,
            'edit_posts' => true,
            'edit_acf_options' => true,
            'upload_files' => true,
            'edit_files' => true,
            'delete_posts' => true,
            'delete_published_posts' => true,
            'manage_options' => false, // Explicitly set this to false
            'access_acf_options' => true, // Add this new capability
            'edit_options' => true // Add this capability
        )
    );
}
add_action('init', 'create_custom_acf_editor_role');


function update_acf_editor_role_capabilities() {
    $role = get_role('acf_options_editor');
    if($role) {
        $role->add_cap('read', true);
        $role->add_cap('edit_posts', true);
        $role->add_cap('edit_acf_options', true);
        $role->add_cap('upload_files', true);
        $role->add_cap('edit_files', true);
        $role->add_cap('delete_posts', true);
        $role->add_cap('delete_published_posts', true);
        $role->add_cap('manage_options', false);
        $role->add_cap('access_acf_options', true);
        $role->add_cap('edit_options', true);
        $role->add_cap('edit_acf_options', true);

          // Add other potentially needed capabilities
    $role->add_cap('manage_options', false);

    // Remove capabilities you don't want
    $role->remove_cap('edit_posts');
    $role->remove_cap('delete_posts');
    $role->remove_cap('delete_published_posts');
    // $role->add_cap('edit_theme_options', true);
    // $role->add_cap('customize', true);
    }
}


// Run this function
add_action('init', 'update_acf_editor_role_capabilities');



function allow_acf_editor_media_access($caps, $cap, $user_id, $args) {
    if ('upload_files' === $cap && user_can($user_id, 'edit_acf_options')) {
        $caps = array('edit_acf_options');
    }
    return $caps;
}
add_filter('map_meta_cap', 'allow_acf_editor_media_access', 10, 4);




function add_acf_options_access($capability) {
    global $pagenow;
    
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'updates') {
        return 'edit_acf_options';
    }
    
    return $capability;
}
add_filter('option_page_capability_updates', 'add_acf_options_access');





// Restrict access to ACF admin menu with administrator exception
function restrict_acf_admin_menu($show_admin) {
    // Always show for administrators
    if (current_user_can('administrator')) {
        return true;
    }
    
    // For ACF Options Editor role
    if (current_user_can('edit_acf_options') && !current_user_can('manage_options')) {
        return false;
    }
    return $show_admin;
}
add_filter('acf/settings/show_admin', 'restrict_acf_admin_menu');


// Add specific capability for managing ACF field groups
function add_acf_capabilities($role) {
    if (is_object($role) && $role->name === 'administrator') {
        $role->add_cap('manage_acf');
        $role->add_cap('edit_acf_fields');
    }
}
add_action('admin_init', function() {
    add_acf_capabilities(get_role('administrator'));
});


// Completely rebuild admin menu for ACF Options Editor
function rebuild_admin_menu_for_acf_editor() {
    if (current_user_can('edit_acf_options') && !current_user_can('manage_options')) {
        global $menu, $submenu;

        // Clear the entire menu
        $menu = array();
        $submenu = array();

        // Add ACF options pages manually
        if (function_exists('acf_get_options_pages')) {
            $option_pages = acf_get_options_pages();
            if ($option_pages) {
                foreach ($option_pages as $page) {
                    add_menu_page(
                        $page['page_title'],
                        $page['menu_title'],
                        'edit_acf_options',
                        $page['menu_slug'],
                        '',
                        'dashicons-admin-generic',
                        30
                    );
                }
            }
        }

        // Remove the default WordPress menu items
        remove_action('admin_menu', '_wp_menu_output');
    }
}
add_action('admin_menu', 'rebuild_admin_menu_for_acf_editor', 9999);

// Redirect to the first ACF options page on login
function redirect_acf_editor_on_login($redirect_to, $request, $user) {
    if (isset($user->roles) && in_array('acf_options_editor', $user->roles)) {
        $option_pages = acf_get_options_pages();
        if (!empty($option_pages)) {
            $first_page = reset($option_pages);
            return admin_url('admin.php?page=' . $first_page['menu_slug']);
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'redirect_acf_editor_on_login', 10, 3);



function disable_homepage_editor() {
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } elseif (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    }

    if (!isset($post_id)) {
        return;
    }

    $homepage_id = get_option('page_on_front');
    
    if ($post_id == $homepage_id) {
        remove_post_type_support('page', 'editor');
        add_action('edit_form_after_title', 'show_homepage_message');
        add_action('admin_head', 'add_custom_admin_css');
    }
}
add_action('admin_init', 'disable_homepage_editor');

function show_homepage_message() {
    echo '<div class="homepage-notice"><p>The homepage content is dynamically created visit the <a href="/wp-admin/admin.php?page=updates">Updates section</a> to edit your content</p></div>';
}

function add_custom_admin_css() {
    echo '
    <style>
        .homepage-notice {
            background: #fff;
            border-left: 4px solid #00a0d2;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            margin: 20px 0;
            padding: 1px 12px;
        }
        .homepage-notice p {
            font-size: 16px;
            margin: 0.5em 0;
            padding: 2px;
        }
    </style>
    ';
}


function enqueue_custom_editor_assets() {
    global $post;
    if ($post && $post->ID == 15) {
        wp_enqueue_script(
            'custom-editor-notice',
            get_template_directory_uri() . '/custom-editor-notice.js',
            array('wp-edit-post', 'wp-element', 'wp-components'),
            filemtime(get_template_directory() . '/custom-editor-notice.js')
        );
    }
}
add_action('enqueue_block_editor_assets', 'enqueue_custom_editor_assets');



// function homepage_content_message($content) {
//     if (is_front_page()) {
//         return '<p>The homepage content is dynamically created</p>';
//     }
//     return $content;
// }
// add_filter('the_content', 'homepage_content_message');









?>
