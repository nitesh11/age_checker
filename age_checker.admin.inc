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

  // Do you want Remember Me option.
  $form['age_checker_option_remember_me'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Would you like to display remember me check box'),
    '#default_value' => variable_get('age_checker_option_remember_me', 0),
  );

  // URL from where we need to get the Country code.
  $form['age_checker_country_code_url'] = array(
    '#title'            => t('URL'),
    '#type'             => 'textfield',
    '#default_value'    => variable_get('age_checker_country_code_url', 'http://geoip.nekudo.com/api/'),
  );

  // Language for age checker.
  $form['lang'] = array(
    '#title'         => t('Age Checker Language'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  $form['lang']['age_checker_language'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Please enter the list of languages in key|value pair.'),
    '#required'      => TRUE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_language', ''),
    '#description'   => t('Please enter required language in key|value format for e.g. es|Español. The name of the key should not have any space.'),
  );
  // Countries
  $form['country'] = array(
    '#title'         => t('Age Checker Countries'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  $form['country']['age_checker_countries'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Please enter the list of countries in key|value pair.'),
    '#required'      => TRUE,
    '#maxlength'     => 255,
    '#default_value' => variable_get('age_checker_countries', ''),
    '#description'   => t('Please enter required country in localized language e.g. ES|España. The key should be picked up from A2 (ISO) column of ' . l('http://www.worldatlas.com/aatlas/ctycodes.htm', 'http://www.worldatlas.com/aatlas/ctycodes.htm', array('attributes' => array('class' => 'sample_link'))) . ' site depending on the value of the country.'),
  );
  // Verification options for age checker.
  $form['options'] = array(
    '#title'         => t('Age Checker Options'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  // Cookie Expiration Time.
  $form['options']['age_checker_cookie_expiration_time'] = array(
    '#title'            => t('Cookie expiration days'),
    '#type'             => 'textfield',
    '#field_suffix'     => t('Days'),
    '#size'             => 6,
    '#element_validate' => array('element_validate_integer'),
    '#default_value'    => variable_get('age_checker_cookie_expiration_time', '15'),
    '#description'      => t('The number of days before the cookie set by age checker module expires, and the user must verify their age again (0 days will expire at end of session).'),
  );
  // Age Checker URL.

  $form['options']['age_checker_under_age_url'] = array(
    '#title'         => t('Enter underage page url'),
    '#type'          => 'textfield',
    '#default_value' => check_url(variable_get('age_checker_under_age_url', '')),
    '#required'      => TRUE,
    '#description'   => t('Please add http:// or https:// for external url  or create a drupal CMS page and enter Drupal path for internal CMS Page. E.g "under-age" for  http://www.example.com/sitename/under-age'),
  );
  
  // Age Checker Visibility.
  $form['options']['age_checker_visibility'] = array(
    '#type'       => 'radios',
    '#title'      => t('Show Age Gate on specific pages'),
    '#options'    => array(
      AGE_CHECKER_VISIBILITY_NOTLISTED => t('Show on all pages except those listed'),
      AGE_CHECKER_VISIBILITY_LISTED    => t('Show only on the listed pages'),
    ),
    '#default_value' => variable_get('age_checker_visibility', AGE_CHECKER_VISIBILITY_NOTLISTED),
  );
  // Age checker specific pages.
  $form['options']['age_checker_pages'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Age gate exception pages'),
    '#default_value' => variable_get('age_checker_pages', 'admin/*'),
    '#description'   => t("Enter the path of the page e.g. enter 'blog' for the blog main page and 'blog/*' for the blog main page and its subpages."),
  );
   
  // Images Age Checker.
  $form['options']['age_checker_background_image'] = array(
    '#type'                      => 'managed_file',
    '#name'                      => 'backgroundimage_image',
    '#title'                     => t('Change the background'),
    '#default_value'             => variable_get('age_checker_background_image', ''),
    '#description'               => t("Here you can upload an image to the background!"),
    '#upload_location'           => 'public://images_age_checker/',
    '#upload_validators'         => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
      'file_validate_size'       => array(1000000),
    ),
  );
  
  // logo Age Checker.
  $form['options']['age_checker_logo'] = array(
    '#type'                      => 'managed_file',
    '#name'                      => 'logo_agechecker',
    '#title'                     => t('Change the logo'),
    '#default_value'             => variable_get('age_checker_logo', ''),
    '#description'               => t("Here you can upload logo!"),
    '#upload_location'           => 'public://images_age_checker/',
    '#upload_validators'         => array(
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

  // validation for countries
  $countries = $form_state['input']['age_checker_countries'];
  $countries = explode("\n", $countries);
  $country_options = array();
  foreach ($countries as $country) {
    $country = explode('|', $country);
    if ($country[1] == '') {
      form_set_error('age_checker_countries', t('Please remove the extra space.'));
    }
    $country_options[$country[0]] = $country[1];
    $countries_list = array_map('trim', $country_options);
  }
  foreach ($countries_list as $country_list) {
   if (preg_match('/[0-9]/', $country_list)) {
      form_set_error('age_checker_countries', t('Please enter proper country name'));
    }
  }
  //validation for languages
  $languages = $form_state['input']['age_checker_language'];
  $languages = explode("\n", $languages);
  $languages_options = array();
  foreach ($languages as $language) {
    $language = explode('|', $language);
    if ($language[1] == '') {
      form_set_error('age_checker_language', t('Please remove the extra space.'));
    }
    $language_options[$language[0]] = $language[1];
    $language_list = array_map('trim', $language_options);
  }
  foreach ($language_list as $language) {
    if (preg_match('/[0-9]/', $language)) {
      form_set_error('age_checker_language', t('Please enter proper language name'));
    }
  }
}
/**
 * @file
 * Function for creating settings form to for basic configuration.
 */
function age_checker_mapping_admin_form($form, &$form_state) {
  $form = array();
  $languages = variable_get('age_checker_language', 'Please provide values');
  $languages = explode("\n", $languages);
  foreach ($languages as $language) {
    $language = explode('|', $language);
    $language = array_map('trim', $language);
    $form[$language[0] . '_mapping'] = array(
      '#type' => 'fieldset',
      '#title' => check_plain($language[1]),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $language = $language[0];

    // Field for selecting the country for a particular language.
    $countries = variable_get('age_checker_countries', '');
    $countries = explode("\n", $countries);
    $country_options = array();
    $country_options[0] = 'Select the Country';
    foreach ($countries as $country) {
      $country = explode('|', $country);
      $country_options[$country[0]] = $country[1];
      $result = array_map('trim', $country_options);
    }

    // Label of Country Field.
    $form[$language . '_mapping']['age_checker_' . $language . '_select_list_label'] = array(
      '#type'          => 'textfield',
      '#title'         => check_plain(t('Label for country select')),
      '#maxlength'     => 255,
      '#required'      => FALSE,
      '#default_value' => variable_get('age_checker_' . $language . '_select_list_label', 'Select your country'),
    );

    $form[$language . '_mapping']['age_checker_' . $language . '_country_list'] = array(
      '#title'         => t('Select country(s)'),
      '#type'          => 'select',
      '#description'   => t('Please select the country(s) to map to this language'),
      '#default_value' => variable_get('age_checker_' . $language . '_country_list', ''),
      '#options'       => $result,
      '#multiple'      => TRUE,
    );

    // Message to be added before the form.
    $message_beforeform = variable_get('age_checker_' . $language . '_age_gate_header', array('value' => '', 'format' => NULL));
    $form[$language . '_mapping']['age_checker_' . $language . '_age_gate_header'] = array(
      '#title'         => t('Welcome and LDA copy'),
      '#type'          => 'text_format',
      '#rows'          => 6,
      '#default_value' => isset($message_beforeform['value']) ? $message_beforeform['value'] : '',
      '#description'   => t('e.g. You must be of legal drinking age to enter this site. Enter you date of birth below.'),
      '#format'        => $message_beforeform['format'],
    );

    // Message to be added after the form.
    $message_afterform = variable_get('age_checker_' . $language . '_age_gate_footer', array('value' => '', 'format' => NULL));
    $form[$language . '_mapping']['age_checker_' . $language . '_age_gate_footer'] = array(
      '#title'         => t('Cookie consent statement copy'),
      '#type'          => 'text_format',
      '#rows'          => 6,
      '#default_value' => isset($message_afterform['value']) ? $message_afterform['value'] : '',
      '#description'   => t('e.g This website uses cookies that are stored on your computer in order to enhance your experience. By providing your date of birth, you also agree to the use of cookies.'),
      '#format'        => $message_afterform['format'],
    );

    // Age checker validation message.
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_blank_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Age Checker Blank Error Message.'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_age_checker_blank_error_msg', 'Date field can not be blank.')),
      '#description'   => t('Enter a helpful and user-friendly message.'),
    );

    // Incorrect Date Format Validation Message
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_dateformat_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Incorrect Date Format Validation Message'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_age_checker_dateformat_error_msg', 'Please enter your date of birth in correct format.')),
      '#description'   => t('Enter a helpful and user-friendly message.'),
    );
      // Date Out Of Range Validation Message
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_daterange_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Date Out Of Range Validation Message'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_age_checker_daterange_error_msg', 'Please enter your date of birth in valid date range.')),
      '#description'   => t('Enter a helpful and user-friendly message.'),
    );

    // Error message for under age.
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_underage_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Under Age Validation Message'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_age_checker_underage_error_msg', 'Sorry, you are Under age limit and are prohibited from entering this site!')),
      '#description'   => t('Enter a helpful and user-friendly message.'),
    );

    // Remember Me Text Configuration.
    $form[$language . '_mapping']['age_checker_' . $language . '_remember_me_text'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Remember Me Text'),
      '#maxlength'     => 255,
      '#required'      => FALSE,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_remember_me_text', 'Remember my details (Do not select on shared computers)')),
      '#description'   => t('Please enter the remember me text.'),
    );

    // Age checker submit button text.
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_button_text'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Form submit button label.'),
      '#maxlength'     => 50,
      '#required'      => TRUE,
      '#default_value' => check_plain(variable_get('age_checker_' . $language . '_age_checker_button_text', 'Submit')),
      '#description'   => t('Please enter submit button text.'),
    );

    // Configuration for adding Footer Links
    $form[$language . '_mapping']['age_checker_' . $language . '_age_checker_footer_links'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Age checker footer links'),
      '#required'      => FALSE,
      '#maxlength'     => 255,
      '#default_value' => variable_get('age_checker_' . $language . '_age_checker_footer_links', ''),
      '#description'   => t('Please enter footer links like Privacy policy, Terms of use, cookie policy in key|value format for e.g - Privacy Policy | http://site.link'),
    );
    
    // Copy right text
    $copyright_text = variable_get('age_checker_' . $language . '_age_checker_copyright', array('value' => '', 'format' => NULL));
    $form[$language . '_mapping'][$language . '_age_checker_copyright'] = array(
      '#type'          => 'text_format',
      '#title'         => t('Please enter copyright text'),
      '#description'   => t('This field is used for the footer copyright text.'),
      '#rows'          => 6,
      '#default_value' => isset($copyright_text['value']) ? $copyright_text['value'] : '',
      '#description'   => t('Copyright © 2015 Brand name. All rights reserved'),
      '#format'        => $copyright_text['format'],
    );
  }
  return system_settings_form($form);
}

// Form Validation.
function age_checker_mapping_admin_form_validate($form, &$form_state) {
  // Get the countries array.
  $countries = variable_get('age_checker_countries', 'Please provide values');
  $countries = explode("\n", $countries);
  foreach ($countries as $country) {
    $country = explode('|', $country);
    $countries_array[] = trim($country[0]);
    $result = array_map('trim', $countries_array);
  }

  $languages = variable_get('age_checker_language');
  $languages = explode("\n", $languages);
  $count = 1;
  $flag = 0;
  $field_name = '';

  // Get the list of all languages.
  foreach ($languages as $language) {
    $language = explode('|', $language);
    $langs[] = trim($language[0]);
  }

  foreach ($langs as $lang) {
    if (count($countries_array) == 0 && (count($langs) != $count)) {
      $flag = 1;
      $field_name = 'age_checker_' . $lang . '_country_list';
      break;
    }
    else {
      $countries_array = array_diff($countries_array, $form_state['input']['age_checker_' . $lang . '_country_list']);
    }
    $count++;
  }

  if ($flag == 1) {
    form_set_error($field_name, "Language Mapping is not proper. You have mapped one country with more than one language.");
  }

  if (count($countries_array) > 0) {
    drupal_set_message("The mapping of few of the countries are missing.", 'error');
  }
}

/**
 * Form callback for age_checker admin settings.
 */
function age_checker_country_configuration() {
  $form = array();

  global $base_url;
  $country_options = array();
  $countries = variable_get('age_checker_countries', '');
  $countries = explode("\n", $countries);
  foreach ($countries as $country) {
    $country_array = explode('|', $country);
    $country_options[$country_array[0]] = $country_array[1];
    $country_list = array_map('trim', $country_options);
  }
  $form['default_country_configuration'] = array(
    '#title'         => t('Default Country Configuration'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => TRUE,
  );
  $form['default_country_configuration']['age_checker_default_country'] = array(
    '#type'          => 'select',
    '#description'   => t('Select the default country of the site.'),
    '#default_value' => variable_get('age_checker_default_country'),
    '#options'       => $country_list,
  );

  $form['ages'] = array(
    '#title'         => t('Legal Drinking Age (LDA) by country'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => FALSE,
  );

  // Minimum Age Checker.
  foreach ($countries as $country) {
    $country_array = explode('|', $country);
    $country_array = array_map('trim', $country_array);
    $form['ages']['age_checker_' . $country_array[0] . '_threshold_ages'] = array(
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#title'            => $country_array[1],
      '#default_value'    => variable_get('age_checker_' . $country_array[0] . '_threshold_ages', 18),
      '#type'             => 'textfield',
      '#size'             => 3,
      '#element_validate' => array('element_validate_integer'),
    );
  }

  // Getting the format of the date field.
  $form['country_specific'] = array(
    '#title'         => t('Country Specific Configuration'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => FALSE,
  );

  foreach ($countries as $country) {
    $country_array = explode('|', $country);
    $country_array = array_map('trim', $country_array);
    $form['country_specific'][$country_array[0]] = array(
      '#title'         => $country_array[1],
      '#type'          => 'fieldset',
      '#collapsible'   => TRUE,
      '#collapsed'     => TRUE,
    );

    // Link for Multilingual site.
    if (module_exists('locale')) {
      // Fieldset for Changing the Date format.
      $form['country_specific'][$country_array[0]]['multilingual'] = array(
        '#title'         => t('Redirect links after age gate.'),
        '#type'          => 'fieldset',
        '#collapsible'   => TRUE,
        '#collapsed'     => TRUE,
      );
      // Changing the weight of Day field.
      $form['country_specific'][$country_array[0]]['multilingual']['age_checker_' . $country_array[0] . '_redirect_link'] = array(
        '#title'            => t('Redirect Link for ') . $country_array[1],
        '#default_value'    => check_url(variable_get('age_checker_' . $country_array[0] . '_redirect_link', $base_url)),
        '#type'             => 'textfield',
        '#size'             => 255,
      );
    }

    // Fieldset for Changing the Date format.
    $form['country_specific'][$country_array[0]]['weight'] = array(
      '#title'         => t('Changing the weight of the date field'),
      '#type'          => 'fieldset',
      '#collapsible'   => TRUE,
      '#collapsed'     => TRUE,
    );
    // Changing the weight of Day field.
    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_day_weight'] = array(
      '#title'            => t('Weight of Day Field :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_day_weight', 1)),
      '#type'             => 'textfield',
      '#size'             => 1,
      '#element_validate' => array('element_validate_integer'),
    );
    // Changing the weight of Month field.
    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_month_weight'] = array(
      '#title'            => t('Weight of Month Field :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_month_weight', 2)),
      '#type'             => 'textfield',
      '#size'             => 1,
      '#element_validate' => array('element_validate_integer'),
    );
    // Changing the weight of Year field.
    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_year_weight'] = array(
      '#title'            => t('Weight of Year field : '),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_year_weight', 3)),
      '#type'             => 'textfield',
      '#size'             => 1,
      '#element_validate' => array('element_validate_integer'),
    );

    // Fieldset for Changing the Placeholder.
    $form['country_specific'][$country_array[0]]['placeholder'] = array(
      '#title'         => t('Changing the placeholder of the date field'),
      '#type'          => 'fieldset',
      '#collapsible'   => TRUE,
      '#collapsed'     => TRUE,
    );
    // Placeholder for Day format.
    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_day_placeholder'] = array(
      '#type'             => 'textfield',
      '#title'            => t('Day Placeholder :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#required'         => FALSE,
      '#size'             => 2,
      '#maxlength'        => 2, 
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_day_placeholder', 'DD')),
    );
    // Placeholder for Month format.
    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_month_placeholder'] = array(
      '#type'             => 'textfield',
      '#title'            => t('Month Placeholder :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#required'         => FALSE,
      '#size'             => 2,
      '#maxlength'        => 2, 
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_month_placeholder', 'MM')),
    );
    // Placeholder for Year format.
    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_year_placeholder'] = array(
      '#type'             => 'textfield',
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#title'            => t('Year Placeholder :'),
      '#required'         => FALSE,
      '#size'             => 4,
      '#maxlength'        => 4, 
      '#default_value'    => check_plain(variable_get('age_checker_' . $country_array[0] . '_year_placeholder', 'YYYY')),
    );
  }
  return system_settings_form($form);
}
