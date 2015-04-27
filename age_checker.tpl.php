<?php
/**
 * @file
 * Theme implementation to display age checker on a single Drupal page.
 *
 * Available variables:
 * - $age_cheker_background_img: With variable will provide background image
 * - $age_cheker_logo: This variable will provide and age gate logo
 * - $age_checker_header_massage: This variable will provide a text below logo
 * - $age_checker_form : This variable will provide form, which include below things
 * - list of countries: This will provide a drop down list of countries
 * - day: This will provide a day field
 * - month: This will provide a month field
 * - year: This will provide a year field
 * - remember_me: This will provide a remember me check box
 * - submit: This will provide submit button
 * - $age_checker_cookies_declaration: This variable will provide text content to describe that this site will use cookies
 * - $age_checker_drinking_statement: This variable will provide a responsible  drinking age statement
 * - $age_checker_copyright: This variable will provide a copyright statement
 */
print $age_cheker_background_img;
print $age_cheker_logo;
print $age_checker_header_massage;
print ( drupal_render($age_checker_form['list_of_countries']) );
print ( drupal_render($age_checker_form['day']) );
print ( drupal_render($age_checker_form['month']) );
print ( drupal_render($age_checker_form['year']) );
print ( drupal_render($age_checker_form['remember_me']) );
print ( drupal_render($age_checker_form['submit']) );
print $age_checker_cookies_declaration;
print $age_checker_drinking_statement;
print $age_checker_copyright;