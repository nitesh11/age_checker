<?php

/**
 * @file
 * Default template file for age gate popup.
 *
 * Available variables:
 * - $header: The header text to output in the popup.
 * - $message: The age verification message to display to the user.
 * - $widget: The age verification widget.
 */
  //dsm($variables);
  ?>
<!--  
<div id="age_checker_verification_popup">
  <div id="age_checker_message">
    <?php //print $beforemessage; ?>
  </div>
  <div id="age_checker_widget">
    <?php //print $widget; ?>
  </div>
   <div id="age_checker_message">
    <?php //print $aftermessage; ?>
  </div>
</div>
-->
<?php dsm($variables); ?>

<?php //dsm($form); ?>
<?php print $agegate_logo ?>
<?php print $text_one; ?>
<?php print ( drupal_render($form['day']) ); ?>

<?php print ( drupal_render($form['month']) ); ?>

<?php print ( drupal_render($form['year']) ); ?>

<?php print ( drupal_render($form['list_of_countries']) ); ?>

<?php print ( drupal_render($form['remember_me']) ); ?>


<?php //print $form ?>


<?php //print $text_one ?>
<?php //print $text_two ?>
<?php print $background_img ?>
