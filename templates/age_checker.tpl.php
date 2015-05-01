<?php
/**
 * @file
 * Theme implementation to display age checker on a single Drupal page.
 *
 * Available variables:
 * - $age_cheker_background_img: With variable will provide background image
 * - $age_cheker_logo: This variable will provide and age gate logo
 * - $age_checker_header_message: This variable will provide a text below logo
 * - $age_checker_form : This variable will provide form, which include below things
 * - $age_checker_cookies_declaration: This variable will provide text content to describe that this site will use cookies
 * - $age_checker_drinking_statement: This variable will provide a responsible  drinking age statement
 * - $age_checker_copyright: This variable will provide a copyright statement
 * - $footer: This variable will provide an array containing footer text and links.
 */
?>

<div id="age_checker_content" class="agegate_content">
  <div id="age_checker_message">
   <?php print $age_checker_header_message; ?>
  </div>

  <div id="age_checker_widget">
    <?php print ( drupal_render($age_checker_form) );?>
  </div>
</div>

<div id="age_checker_declaration">
  <?php print $age_checker_cookies_declaration; ?>
</div>

<div id="age_checker_drinking_statement">
  <?php print $age_checker_drinking_statement; ?>
</div>

<div id="age_checker_copyright">
  <?php print $age_checker_copyright; ?>
</div>
