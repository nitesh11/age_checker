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
print $age_cheker_background_img;
print $age_cheker_logo;
print $age_checker_header_message;
print (drupal_render($age_checker_form));
print $age_checker_cookies_declaration;
print $age_checker_drinking_statement;
print $age_checker_copyright;