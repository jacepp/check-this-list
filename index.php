<?php
/*
Plugin Name: Check This list
Plugin URI: http://
Version: 1.0
Description: Custom pluging
Author: Jace Poirier-Pinto
Author URI: http://jacewdim393f.wordpress.com
License: GNU General Public License v2 or later
*/

function ctl_my_enqueue($hook) {
    if( 'post-new.php' != $hook ) {
      if( 'settings_page_ctl_options_page' != $hook ) {
        return;
      }
    }
        
    wp_enqueue_script( 'ctl_my_custom_script', plugin_dir_url( __FILE__ ) . '/ctl.js' );
}
add_action( 'admin_enqueue_scripts', 'ctl_my_enqueue' );

function ctl_add_options_page() {
  add_options_page(
    __( 'Check This List' ),
    __( 'Check This List' ),
    'manage_options',
    'ctl_options_page',
    'ctl_render_options_page'
  );
}
add_action( 'admin_menu', 'ctl_add_options_page' );

function ctl_render_options_page() {
  ?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e( 'Check This List' ); ?></h2>
    <form action="options.php" method="post">
      <input name="page_options" type="hidden" value="ctl_create_list" />
      <?php settings_fields( 'ctl_option_group' ); ?>
      <?php do_settings_sections( 'ctl_options_page' ); ?>
      <div id="buttons">
        <button id="add">Add Task</button>
        <button id="minus">Remove Task</button>
      </div>
      <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>">
      </p>
    </form>
  </div>
  <?php
}

function ctl_add_settings() {
  register_setting(
    'ctl_option_group',
    'ctl_disable_button',
    'absint'
  );

  register_setting(
    'ctl_option_group',
    'ctl_create_list'
  );

  add_settings_section(
    'ctl_main_settings',
    __( 'Plugin Options' ),
    'ctl_render_main_settings_section',
    'ctl_options_page'
  );

  add_settings_field(
    'ctl_disable_button_field',
    __( 'Disable Check This List on Posts' ),
    'ctl_render_disable_button_input',
    'ctl_options_page',
    'ctl_main_settings'
  );

  if ( '0' === get_option( 'ctl_disable_button', '0' ) ) {
    add_settings_field(
      'ctl_create_list_field',
      __( 'Create a list of tasks:' ),
      'ctl_render_list_input',
      'ctl_options_page',
      'ctl_main_settings'
    );
  }
}
add_action( 'admin_init', 'ctl_add_settings' );

function ctl_render_main_settings_section() {
  _e( '<p>Main settings for the Check This List Plugin.</p>' );
}

function ctl_render_disable_button_input() {
  $current = get_option( 'ctl_disable_button', 0 );
  echo '<input id="ctl-disable-button" name="ctl_disable_button" type="checkbox" value="1"'. checked( 1, $current, false ) .'/>';
}

function ctl_render_list_input() {
  $current = get_option( 'ctl_create_list' );
  if ( !empty( $current ) ) {
    $i = 0;
    foreach ( $current as $item ) {
      echo '<input style="display:block;" id="ctl-create-list-'. $i .'" size="50" name="ctl_create_list['. $i .']" type="text" value="'. esc_html( $current[$i] ) .'" required />';
    } 
  } else {
    echo '<input style="display:block;" id="ctl-create-list-0" size="50" name="ctl_create_list[0]" type="text" value="" required />';
  }
}

/*
 *
 * Meta Box stuff
 * 
 */


function ctl_call_meta_box( $post_type, $post ) {
  add_meta_box(
    'check_this_list',
    __( 'Check This List', 'ctl_plugin' ),
    'ctl_display_meta_box',
    'post',
    'side',
    'high'
  );
}

add_action( 'add_meta_boxes', 'ctl_call_meta_box', 10, 2 );

function ctl_display_meta_box( $post, $args ) {
  if ( '0' === get_option( 'ctl_disable_button', '0' ) ) {
    $current = get_option( 'ctl_create_list' );
    
    if ( empty( $current ) ) {
      return;
    }

    _e( '<p id="disabled">You must complete all of these tasks before publishing.</p>' );

    $i = 1;
    foreach ( $current as $item ) {
      echo '<label style="display:block;" for="cb'. $i .'"><input class="chkbx" id="cb'. $i .'" name="cb'. $i .'" type="checkbox" value="'. $i++ .'" /> '. esc_html( $item ) .' </label>';
    }
  } else {
    _e( '<p id="disabled">Check this list is disabled</p>' );
  }
}

