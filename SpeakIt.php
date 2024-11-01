<?php
/**
 * @package SpeakIt
 */
/*
Plugin Name: SpeakIt
Plugin URI: https://www.mobibrw.com/speakit
Description: Plugin for Speak
Version: 1.0
Author: longsky
Author URI: https://www.mobibrw.com
License: GPLv2 or later
Text Domain: SpeakIt
*/
?>
<?php
require_once('SpeakItFunc.php');
?>
<?php
  function speakit_main($content) {
    if(is_single()||is_feed()) {
      // load_speakit_html from SpeakItFunc.php
      $html = speakit_load_html($content);
      $content = $html.$content;
    }
    return $content;
  }
  add_filter ('the_content', 'speakit_main');
?>
