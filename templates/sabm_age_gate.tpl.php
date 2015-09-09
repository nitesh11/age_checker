<?php
/**
 * @file
 * Theme implementation to display age checker on a single Drupal page.
 *
 * Available variables:
 * - $age_checker_header_message: This variable will provide a text below logo
 * - $age_checker_form : This variable will provide form, which include below things
 * - $age_checker_footer_message: This variable provided the footer text of the form.
 */
?>

<div id="age_checker_content" class="agegate_content">
  <div id="age_checker_header_message">
   <?php print $age_checker_header_message; ?>
  </div>

  <div id="age_checker_widget">
		<div id="age_checker_error_message"> </div>
    <?php print ( drupal_render($age_checker_form) );?>
  </div>
</div>

<div id="age_checker_footer_message">
  <?php print $age_checker_footer_message; ?>
</div>
