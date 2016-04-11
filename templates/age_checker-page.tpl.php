<?php
/**
 * @file
 * Theme implementation to display age checker on a single Drupal page.
 *
 * Available variables:
 * - $age_cheker_background_img: This variable has the background image.
 * - $age_cheker_logo : This variable has the logo image of the page.
 * - $age_checker_footer_links: An array having all the values in key value pair.
 * - $age_checker_copyright : Copyright text for the footer of the age gate.
 */
?>

<div id="age_checker">
  <div class="age-checker-bg">
    <?php print $age_cheker_background_img; ?>
  </div>
  <div class="logo">
    <?php print $age_cheker_logo; ?>
  </div>

  <div class="agegate-container">
    <?php print render($page['content']); ?>
  </div>

  <div class="agegate-container-footer">
    <?php if (count($age_checker_footer_links) > 0) { ?>
      <div class="agegate-footer">
        <ul class="agegatelinks-menu">
          <?php
            foreach ($age_checker_footer_links as $key => $value) {
              if (end($age_checker_footer_links) == $value) { ?>
                <li class="leaf last">
                  <a href="<?php print $value ?>"> <?php print $key ?> </a>
                </li>
              <?php }
              else { ?>
                <li class="leaf">
                  <a href="<?php print $value ?>"> <?php print $key ?> </a> |
                </li>
              <?php }
            } ?>
        </ul>
      </div>
    <?php } ?>
    <?php if (!empty($age_checker_copyright)) { ?>
      <div class="agegate_copyright_text">
        <?php print render($age_checker_copyright); ?>
      </div>
    <?php } ?>
  </div>
</div>
