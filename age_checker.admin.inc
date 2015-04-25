<?php
/**
 * @file
 * Admin functionality for age_checker module.
 */

/**
 * Form callback for age_checker admin settings.
 */
function age_checker_admin_form() {
  $form = array();
    // Language for age checker.
  $form['lang'] = array(
    '#title'         => t('Age Checker Language'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  $form['lang']['language'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Please enter your language'),
    '#required'      => TRUE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('language', ''),
    '#description'   => t('Please enter required language'),
  );
  // Countries
  $form['country'] = array(
    '#title'         => t('Age Checker Countries'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  $form['country']['countries'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Please enter your countries'),
    '#required'      => TRUE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('countries', ''),
    '#description'   => t('Please enter required language'),
  );
  
  // Verification options for age checker.
  $form['options'] = array(
    '#title'         => t('Age Checker Options'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  
  // Cookie Expiration Time.
  $age_checker_cookie_expiration_time = variable_get('age_checker_cookie_expiration_time', '15');
  $form['options']['age_checker_cookie_expiration_time'] = array(
    '#title'            => t('Cookie expiration days'),
    '#type'             => 'textfield',
    '#field_suffix'     => t('Days'),
    '#size'             => 6,
    '#element_validate' => array('element_validate_integer'),
    '#default_value'    => isset($age_checker_cookie_expiration_time) ? $age_checker_cookie_expiration_time : '15',
    '#description'      => t('The number of days before the cookie set by age checker module expires, and the user must verify their age again (0 days will expire at end of session).'),
  );
  // Age Checker URL.

  $under_age_url = variable_get('under_age_url', '');
  $form['options']['under_age_url'] = array(
    '#title'         => t('Enter underage page url'),
    '#type'          => 'textfield',
    '#default_value' => isset($under_age_url) ? $under_age_url : '',
    '#required'      => TRUE,
    '#description'   => t('Please add http:// or https:// for external url  or create a drupal CMS page and enter Drupal path for internal CMS Page. E.g "under-age" for  http://www.example.com/sitename/under-age'),
  );
  
  // Age Checker Visibility.
  $age_checker_visibility = variable_get('age_checker_visibility', AGE_CHECKER_VISIBILITY_NOTLISTED);
  $form['options']['age_checker_visibility'] = array(
    '#type'       => 'radios',
    '#title'      => t('Show Age Gate on specific pages'),
    '#options'    => array(
      AGE_CHECKER_VISIBILITY_NOTLISTED => t('Show on all pages except those listed'),
      AGE_CHECKER_VISIBILITY_LISTED    => t('Show only on the listed pages'),
    ),
    '#default_value' => isset($age_checker_visibility) ? $age_checker_visibility : AGE_CHECKER_VISIBILITY_NOTLISTED,
  );
  // Age checker specific pages.
  $age_checker_pages = variable_get('age_checker_pages', 'admin/*');
  $form['options']['age_checker_pages'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Type Age Gate specific pages'),
    '#default_value' => isset($age_checker_pages) ? $age_checker_pages : 'admin/*\nuser',
    '#description'   => t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are blog for the blog page and blog/* for every personal blog. <front> is the front page."),
  );
   
  // Images Age Checker.
  $age_checker_background_image = variable_get('age_checker_background_image', '');
  $form['options']['age_checker_background_image'] = array(
    '#type'                    => 'managed_file',
    '#name'                    => 'backgroundimage_image',
    '#title'                   => t('Change the background'),
    '#default_value'           => isset($age_checker_background_image) ? $age_checker_background_image : '',
    '#description'             => t("Here you can upload an image to the background!"),
    '#upload_location'         => 'public://images_age_checker/',
    '#upload_validators'       => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
      'file_validate_size'       => array(1000000),
    ),
  );
  
  // logo Age Checker.
  $age_checker_logo = variable_get('age_checker_logo', '');
  $form['options']['age_checker_logo'] = array(
    '#type'                    => 'managed_file',
    '#name'                    => 'logo_agechecker',
    '#title'                   => t('Change the logo'),
    '#default_value'           => isset($age_checker_logo) ? $age_checker_logo : '',
    '#description'             => t("Here you can upload logo!"),
    '#upload_location'         => 'public://images_age_checker/',
    '#upload_validators'       => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
      'file_validate_size'       => array(1000000),
    ),
  );
  return system_settings_form($form);
}

/**
 * Validate if user entered numeric values.
 */
function age_checker_admin_form_validate($form, &$form_state) {
  //validation for countries
  $countries = $form_state['input']['countries'];
  $countries = explode(',', $countries);
      foreach ($countries as $country) {
      if (preg_match('/[0-9]/', $country)) {
      form_set_error('countries', t('Please enter proper country name'));
    }
  }
  //validation for languages
  $languages = $form_state['input']['language'];
  $languages = explode(',', $languages);
      foreach ($languages as $language) {
      if (preg_match('/[0-9]/', $language)) {
      form_set_error('language', t('Please enter proper language name'));
    }
  }
}

/**
 * @file
 * Function for creating settings form to for basic configuration.
 */
function age_checker_mapping_admin_form($form, &$form_state) {
  $form = array();
  $languages = variable_get('language', 'Please provide values');
  dsm($languages);
  $languages = explode(',', $languages);
   foreach ($languages as $language) {
    $language = trim($language);
    $form[$language . 'mapping'] = array(
      '#type' => 'fieldset',
      '#title' => check_plain($language),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    $form[$language . 'mapping'][$language . 'content_below_logo'] = array(
      '#type' => 'textarea',
      '#title' => t('Please enter a text to place below logo'),
      '#description' => t('This content will be place below the header section of age gate'),
      '#default_value' => variable_get($language . 'content_below_logo', ''),
      '#required' => FALSE,
    );
    $form[$language . 'mapping'][$language . 'copy_right_term_and_condition'] = array(
      '#type' => 'textarea',
      '#title' => t('Please enter copy right, terms & condition'),
      '#description' => t('Above content will be use as a copy right, terms and condition'),
      '#default_value' => variable_get($language . '_gov_max_donation_val', ''),
      '#required' => FALSE,
    );
    $countries = variable_get('countries', 'Please provide values');
    $countries = explode(',', $countries);
    $form[$language . 'mapping'][$language . 'country_list'] = array(
    '#title' => t('Please select country'),
    '#type' => 'select',
    '#description' => t('please select country that would like to map with language'),
    '#options' => $countries,
    '#multiple' => TRUE,
);
  // Message Before the Form.
  $message_beforeform = variable_get('age_checker_before_form_text', array('value' => '', 'format' => NULL));
  $form[$language . 'mapping'][$language . 'age_checker_before_form_text'] = array(
    '#title'         => t('Enter before text'),
    '#type'          => 'text_format',
    '#rows'          => 6,
    '#default_value' => $message_beforeform['value'] ? $message_beforeform['value'] : '',
    '#description'   => t('The message displayed before the Form.'),
    '#format'        => $message_beforeform['format'],
  );
  // Message After the Form.
  $message_afterform = variable_get('age_checker_after_form_text', array('value' => '', 'format' => NULL));
  $form[$language . 'mapping'][$language . 'age_checker_after_form_text'] = array(
    '#title'         => t('Enter after Text'),
    '#type'          => 'text_format',
    '#rows'          => 6,
    '#default_value' => $message_afterform['value'] ? $message_afterform['value'] : '',
    '#description'   => t('The message displayed after the Form'),
    '#format'        => $message_afterform['format'],
  );
   // Age checker validation message.
  $form[$language . 'mapping'][$language . 'age_checker_blank_error_msg'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Age Checker Blank Validation Message'),
    '#required'      => FALSE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_blank_error_msg', 'Date field can not be blank.'),
    '#description'   => t('Please enter message text to populate in age checker validation.'),
  );
    // Incorrect Date Format Validation Message
    $form[$language . 'mapping'][$language . 'age_checker_dateformat_error_msg'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Incorrect Date Format Validation Message'),
    '#required'      => FALSE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_dateformat_error_msg', 'Please enter your date of birth in correct format.'),
    '#description'   => t('Please enter message text to populate in age checker validation.'),
  );
    // Date Out Of Range Validation Message
    $form[$language . 'mapping'][$language . 'age_checker_daterange_error_msg'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Date Out Of Range Validation Message'),
    '#required'      => FALSE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_daterange_error_msg', 'Please enter your date of birth in valid date range.'),
    '#description'   => t('Please enter message text to populate in age checker validation.'),
  );
  // Error message for under age.
  $form[$language . 'mapping'][$language . 'age_checker_underage_error_msg'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Under Age Validation Message'),
    '#required'      => FALSE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_underage_error_msg', 'Sorry, you are Under age limit and are prohibited from entering this site!'),
    '#description'   => t('Please enter error message for under age checker validation'),
  );
  // Age checker submit button text.
  $form[$language . 'mapping'][$language . 'age_checker_button_text'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Button Text'),
    '#maxlength'     => 50,
    '#required'      => FALSE,
    '#default_value' => variable_get('age_checker_button_text', 'Begin your discovery'),
    '#description'   => t('Please enter submit button text.'),
  );
  }
  return system_settings_form($form);
}

/**
 * Form callback for age_checker admin settings.
 */
function age_checker_default_drinking_age_admin_form() {
  $form = array();
  // Minimum Age Checker.
  $age_checker_threshold_age = variable_get('age_checker_threshold_age', 18);
  $form['options']['age_checker_threshold_age'] = array(
    '#title'            => t('Enter threshold age limit in whole years ie. 18'),
    '#type'             => 'textfield',
    '#size'             => 3,
    '#element_validate' => array('element_validate_integer'),
    '#default_value'    => isset($age_checker_threshold_age) ? $age_checker_threshold_age : '18',
    '#description'      => t('The minimum age a user can be to pass the age checker.'),
  );

  $form['option_remember_me'] = array(
    '#type' => 'checkbox',
    '#title' => t('Would you like to display remember me check box'),
    '#default_value' => 0,
  );
  return system_settings_form($form);
}

