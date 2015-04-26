<?php
print $background_img;
print $agegate_logo;
print $content_below_logo;
print ( drupal_render($form['list_of_countries']) );
print ( drupal_render($form['day']) );
print ( drupal_render($form['month']) );
print ( drupal_render($form['year']) );
print ( drupal_render($form['remember_me']) );
print ( drupal_render($form['submit']) );
print $cookies_declaration;
print $responsible_drinking_statement;
print $copyright;

