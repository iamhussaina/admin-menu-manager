<?php
/**
 * Hussain's Admin Menu Manager Snippet
 *
 * A procedural snippet to hide specific WordPress admin menu items
 * based on user roles. This is intended to be included via a theme's
 * functions.php or as an mu-plugin.
 *
 * @package     HussainasAdminMenuManager
 * @version     1.0.0
 * @author      Hussain Ahmed Shrabon
 * @license     MIT
 * @link        https://github.com/iamhussaina
 * @textdomain  hussainas
 */

// Ensure this file is not accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * --------------------------------------------------------------------------
 * CONFIGURATION
 * --------------------------------------------------------------------------
 *
 * Define which menu items to hide for which roles.
 * This makes the snippet easy to configure without touching the core logic.
 *
 */

/**
 * Returns the list of user roles that the restrictions should apply to.
 *
 * @since 1.0.0
 * @return array The list of target user roles (e.g., 'editor', 'author').
 */
function hussainas_get_restricted_roles() {
    /**
     * Filter the roles that should have menu items hidden.
     * @param array $roles List of role slugs.
     */
    return apply_filters( 'hussainas_restricted_roles', array(
        'editor',
        'author',
        'subscriber',
        'contributor',
    ) );
}

/**
 * Returns the list of top-level menu slugs to hide.
 *
 * To find a slug, look at the URL in your admin dashboard.
 * Example: 'tools.php', 'edit-comments.php', 'upload.php'
 *
 * @since 1.0.0
 * @return array The list of top-level menu slugs.
 */
function hussainas_get_top_level_menus_to_hide() {
    /**
     * Filter the top-level menu slugs to hide.
     * @param array $slugs List of menu slugs.
     */
    return apply_filters( 'hussainas_top_level_menus_to_hide', array(
        'tools.php',            // Tools
        'options-general.php',  // Settings
        'edit-comments.php',    // Comments
    ) );
}

/**
 * Returns the list of submenu items to hide.
 *
 * This is useful for hiding specific items without hiding the whole
 * top-level menu (e.g., hiding 'Themes' under 'Appearance').
 *
 * Format: 'parent_slug.php' => array( 'submenu_slug.php', 'another_slug.php' )
 *
 * @since 1.0.0
 * @return array An associative array of submenu items to hide.
 */
function hussainas_get_submenu_items_to_hide() {
    /**
     * Filter the submenu items to hide.
     * @param array $submenus Associative array of parent_slug => [child_slugs].
     */
    return apply_filters( 'hussainas_submenu_items_to_hide', array(
        // Example: Hide 'Themes', 'Widgets', and 'Customize' under 'Appearance'
        'themes.php' => array(
            'themes.php',       // Appearance -> Themes
            'widgets.php',      // Appearance -> Widgets
            'customize.php',    // Appearance -> Customize
        ),
        
        // Example: Hide 'All Pages' under 'Pages'
        // 'edit.php?post_type=page' => array(
        //     'edit.php?post_type=page',
        // ),
    ) );
}

/**
 * --------------------------------------------------------------------------
 * EXECUTION LOGIC
 * --------------------------------------------------------------------------
 *
 * Do not edit below this line unless you are a developer.
 *
 */

// Hook into the 'admin_menu' action with a late priority (999)
// to ensure it runs after default menus are registered.
add_action( 'admin_menu', 'hussainas_hide_admin_menu_items', 999 );

/**
 * The core function that checks the user role and removes menu items.
 *
 * @since 1.0.0
 * @return void
 */
function hussainas_hide_admin_menu_items() {
    
    // 1. Get the current user and their roles
    $current_user = wp_get_current_user();
    if ( ! $current_user instanceof WP_User || $current_user->ID === 0 ) {
        return; // Not a logged-in user
    }
    $user_roles = (array) $current_user->roles;

    // 2. Get the roles we want to restrict
    $restricted_roles = hussainas_get_restricted_roles();

    // 3. Check if the current user's role is in the restricted list
    // array_intersect checks if any of the user's roles match the restricted roles
    $is_restricted_user = ! empty( array_intersect( $user_roles, $restricted_roles ) );

    // 4. Safegaurd: Always allow administrators to see everything.
    // If the user is an admin OR is not in the restricted list, stop execution.
    if ( in_array( 'administrator', $user_roles, true ) || ! $is_restricted_user ) {
        return;
    }

    // 5. Remove Top-Level Menu Pages
    $top_level_slugs = hussainas_get_top_level_menus_to_hide();
    foreach ( $top_level_slugs as $slug ) {
        remove_menu_page( $slug );
    }

    // 6. Remove Submenu Pages
    $submenu_items = hussainas_get_submenu_items_to_hide();
    foreach ( $submenu_items as $parent_slug => $child_slugs ) {
        if ( is_array( $child_slugs ) ) {
            foreach ( $child_slugs as $child_slug ) {
                remove_submenu_page( $parent_slug, $child_slug );
            }
        }
    }
}

/**
 * Helper function (if needed) to debug and view all registered menu slugs.
 * Uncomment the 'add_action' line to use it.
 * This will print all menu slugs in the admin footer for administrators.
 */
// add_action( 'admin_footer', 'hussainas_print_menu_slugs' );
function hussainas_print_menu_slugs() {
    if ( ! current_user_can( 'administrator' ) ) {
        return;
    }

    global $menu, $submenu;

    echo '<pre style="position: fixed; bottom: 0; left: 0; background: #fff; padding: 20px; z-index: 9999; max-height: 300px; overflow-y: scroll; border: 1px solid #ccc;">';
    echo '<strong>TOP LEVEL MENUS:</strong><br>';
    if ( ! empty( $menu ) ) {
        foreach ( $menu as $item ) {
            if ( ! empty( $item[2] ) ) {
                echo esc_html( $item[0] ) . ' -> ' . esc_html( $item[2] ) . '<br>';
            }
        }
    }

    echo '<hr><strong>SUB MENUS:</strong><br>';
    if ( ! empty( $submenu ) ) {
        foreach ( $submenu as $parent_slug => $items ) {
            echo '<strong>' . esc_html( $parent_slug ) . ' (Parent)</strong><br>';
            foreach ( $items as $item ) {
                if ( ! empty( $item[2] ) ) {
                    echo '... ' . esc_html( $item[0] ) . ' -> ' . esc_html( $item[2] ) . '<br>';
                }
            }
        }
    }
    echo '</pre>';
}
