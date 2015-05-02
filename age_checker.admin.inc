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
  $form['option_remember_me'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Would you like to display remember me check box'),
    '#default_value' => variable_get('option_remember_me', 0),
  );
  
  // Variable to changing the GeoIP site.
  $form['geoip_link'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Enter the url for getting the country name of user.'),
    '#default_value' => variable_get('geoip_link', 'http://freegeoip.net/json/'),
    '#description'   => t('Site link'),
  );

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

  $form['options']['under_age_url'] = array(
    '#title'         => t('Enter underage page url'),
    '#type'          => 'textfield',
    '#default_value' => variable_get('under_age_url', ''),
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
    '#title'         => t('Type Age Gate specific pages'),
    '#default_value' => variable_get('age_checker_pages', 'admin/*'),
    '#description'   => t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are blog for the blog page and blog/* for every personal blog. <front> is the front page."),
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
  //validation for countries
  $countries = $form_state['input']['countries'];
  $countries = explode("\n", $countries);
  $country_options = array();
    foreach ($countries as $country) {
      $country = explode('|', $country);
      $country_options[$country[0]] = $country[1];
      $countries_list = array_map('trim', $country_options);
    }
    foreach ($countries_list as $country_list) {
     if (preg_match('/[0-9]/', $country_list)) {
        form_set_error('countries', t('Please enter proper country name'));
      }
    }
  //validation for languages
  $languages = $form_state['input']['language'];
  $languages = explode("\n", $languages);
  $languages_options = array();
    foreach ($languages as $language) {
      $language = explode('|', $language);
      $language_options[$language[0]] = $language[1];
      $language_list = array_map('trim', $language_options);
    }
    foreach ($language_list as $language) {
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
  $languages = explode("\n", $languages);
  foreach ($languages as $language) {
    $language = explode('|', $language);
    $form[$language[0] . 'mapping'] = array(
      '#type' => 'fieldset',
      '#title' => check_plain($language[1]),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $language = $language[0];

    // Field for selecting the country for a particular language.
    $countries = variable_get('countries', '');
    $countries = explode("\n", $countries);
    $country_options = array();
    foreach ($countries as $country) {
      $country = explode('|', $country);
      $country_options[$country[0]] = $country[1];
      $result = array_map('trim', $country_options);
    }

    // Label of Country Field.
    $form[$language . 'mapping'][$language . '_select_list_label'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Enter the text for label for the select list of country.'),
      '#maxlength'     => 255,
      '#required'      => FALSE,
      '#default_value' => variable_get($language . '_select_list_label', 'Select your country'),
    );

    $form[$language . 'mapping'][$language . '_country_list'] = array(
      '#title'         => t('Please select country'),
      '#type'          => 'select',
      '#description'   => t('please select country that would like to map with language'),
      '#default_value' => variable_get($language . '_country_list', ''),
      '#options'       => $result,
      '#multiple'      => TRUE,
    );

    // Message to be added before the form.
    $message_beforeform = variable_get($language . '_age_gate_header', array('value' => '', 'format' => NULL));
    $form[$language . 'mapping'][$language . '_age_gate_header'] = array(
      '#title'         => t('Enter the text to be placed before the form.'),
      '#type'          => 'text_format',
      '#rows'          => 6,
      '#default_value' => $message_beforeform['value'] ? $message_beforeform['value'] : '',
      '#format'        => $message_beforeform['format'],
    );

    // Message to be added after the form.
    $message_afterform = variable_get($language . '_age_gate_footer', array('value' => '', 'format' => NULL));
    $form[$language . 'mapping'][$language . '_age_gate_footer'] = array(
      '#title'         => t('Enter the text to be placed after the form.'),
      '#type'          => 'text_format',
      '#rows'          => 6,
      '#default_value' => $message_afterform['value'] ? $message_afterform['value'] : '',
      '#format'        => $message_afterform['format'],
    );

    // Age checker validation message.
    $form[$language . 'mapping'][$language . '_age_checker_blank_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Age Checker Blank Error Message.'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => variable_get($language . '_age_checker_blank_error_msg', 'Date field can not be blank.'),
      '#description'   => t('Please enter message text to populate in age checker validation.'),
    );

    // Incorrect Date Format Validation Message
    $form[$language . 'mapping'][$language . '_age_checker_dateformat_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Incorrect Date Format Validation Message'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => variable_get($language . '_age_checker_dateformat_error_msg', 'Please enter your date of birth in correct format.'),
      '#description'   => t('Please enter message text to populate in age checker validation.'),
    );
      // Date Out Of Range Validation Message
    $form[$language . 'mapping'][$language . '_age_checker_daterange_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Date Out Of Range Validation Message'),
      '#required'      => FALSE,
      '#maxlength'     => 255,
      '#default_value' => variable_get($language . '_age_checker_daterange_error_msg', 'Please enter your date of birth in valid date range.'),
      '#description'   => t('Please enter message text to populate in age checker validation.'),
    );

    // Error message for under age.
    $form[$language . 'mapping'][$language . '_age_checker_underage_error_msg'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Under Age Validation Message'),
      '#required'      => TRUE,
      '#maxlength'     => 255,
      '#default_value' => variable_get($language . '_age_checker_underage_error_msg', 'Sorry, you are Under age limit and are prohibited from entering this site!'),
      '#description'   => t('Please enter error message for under age checker validation'),
    );

    // Remember Me Text Configuration.
    $form[$language . 'mapping'][$language . '_remember_me_text'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Remember Me Text'),
      '#maxlength'     => 255,
      '#required'      => FALSE,
      '#default_value' => variable_get($language . '_remember_me_text', 'Remember my details (Do not select on shared computers)'),
      '#description'   => t('Please enter the remember me text.'),
    );

    // Age checker submit button text.
    $form[$language . 'mapping'][$language . '_age_checker_button_text'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Form Submit button Configuration.'),
      '#maxlength'     => 50,
      '#required'      => FALSE,
      '#default_value' => variable_get($language . '_age_checker_button_text', 'Begin your discovery'),
      '#description'   => t('Please enter submit button text.'),
    );

    // Configuration for adding Footer Links
    $form[$language . 'mapping'][$language . '_age_checker_footer_links'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Age checker footer links'),
      '#required'      => FALSE,
      '#maxlength'     => 255,
      '#default_value' => variable_get($language . '_age_checker_footer_links', ''),
      '#description'   => t('Please enter footer links like, Privacy policy, Terms of use, cookie policy'),
    );
    // Copy right text
    $form[$language . 'mapping'][$language . '_age_checker_copyright'] = array(
      '#type'          => 'textarea',
      '#title'         => t('Please enter copyright'),
      '#description'   => t('Above content will be use as a copyright'),
      '#default_value' => variable_get($language . '_age_checker_copyright', ''),
      '#required'      => TRUE,
    );
  }
  return system_settings_form($form);
}

/**
 * Form callback for age_checker admin settings.
 */
function age_checker_country_configuration() {
  $form = array();

  global $base_url;
  $country_options = array();
  $countries = variable_get('countries', '');
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
  $form['default_country_configuration']['default_country'] = array(
    '#type'          => 'select',
    '#description'   => t('Select the default country of the site.'),
    '#default_value' => variable_get('default_country'),
    '#options'       => $country_list,
  );

  $form['ages'] = array(
    '#title'         => t('Threshold Ages of Countries'),
    '#type'          => 'fieldset',
    '#collapsible'   => TRUE,
    '#collapsed'     => FALSE,
  );

  // Minimum Age Checker.
  foreach ($countries as $country) {
    $country_array = explode('|', $country);
    $form['ages'][$country_array[0] . '_threshold_ages'] = array(
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#title'            => $country_array[1],
      '#default_value'    => variable_get($country_array[0] . '_threshold_ages', 18),
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
      $form['country_specific'][$country_array[0]]['multilingual'][$country_array[0] . '_redirect_link'] = array(
        '#title'            => t('Redirect Link for ') . $country_array[1],
        '#default_value'    => variable_get($country_array[0] . '_redirect_link', $base_url),
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
    $form['country_specific'][$country_array[0]]['weight'][$country_array[0] . '_day_weight'] = array(
      '#title'            => t('Weight of Day Field :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => variable_get($country_array[0] . '_day_weight', 1),
      '#type'             => 'textfield',
      '#size'             => 1,
      '#element_validate' => array('element_validate_integer'),
    );
    // Changing the weight of Month field.
    $form['country_specific'][$country_array[0]]['weight'][$country_array[0] . '_month_weight'] = array(
      '#title'            => t('Weight of Month Field :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => variable_get($country_array[0] . '_month_weight', 2),
      '#type'             => 'textfield',
      '#size'             => 1,
      '#element_validate' => array('element_validate_integer'),
    );
    // Changing the weight of Year field.
    $form['country_specific'][$country_array[0]]['weight'][$country_array[0] . '_year_weight'] = array(
      '#title'            => t('Weight of Year field : '),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#default_value'    => variable_get($country_array[0] . '_year_weight', 3),
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
    $form['country_specific'][$country_array[0]]['placeholder'][$country_array[0] . '_day_placeholder'] = array(
      '#type'             => 'textfield',
      '#title'            => t('Day Placeholder :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#required'         => FALSE,
      '#size'             => 2,
      '#default_value'    => variable_get($country_array[0] . '_day_placeholder', 'DD'),
    );
    // Placeholder for Month format.
    $form['country_specific'][$country_array[0]]['placeholder'][$country_array[0] . '_month_placeholder'] = array(
      '#type'             => 'textfield',
      '#title'            => t('Month Placeholder :'),
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#required'         => FALSE,
      '#size'             => 2,
      '#default_value'    => variable_get($country_array[0] . '_month_placeholder', 'MM'),
    );
    // Placeholder for Year format.
    $form['country_specific'][$country_array[0]]['placeholder'][$country_array[0] . '_year_placeholder'] = array(
      '#type'             => 'textfield',
      '#prefix'           => '<div class="container-inline">',
      '#suffix'           => '</div>',
      '#title'            => t('Year Placeholder :'),
      '#required'         => FALSE,
      '#size'             => 4,
      '#default_value'    => variable_get($country_array[0] . '_year_placeholder', 'YYYY'),
    );
  }
  return system_settings_form($form);
}