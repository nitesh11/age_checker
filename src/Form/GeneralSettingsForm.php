<?php

/**
 * @file
 * Contains \Drupal\age_checker\Form\GeneralSettingsForm.
 */

namespace Drupal\age_checker\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Edit config variable form.
 */
class GeneralSettingsForm extends ConfigFormBase {

	/**
   * FormID of the form.
   */
	public function getFormId() {
    return 'age_checker_admin_form';
  }

  /**
   * Get the Editable Configurable form base.
   */
  public function getEditableConfigNames() {
    return [
      'age_checker.settings',
    ];
  }

  /**
   * Building the Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

  	$form = array();
  	$age_checker_config = $this->config('age_checker.settings');

	  // Remember me checkbox for Age Gate.
	  $form['age_checker_option_remember_me'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Would you like to display remember me check box'),
	    '#default_value' => $age_checker_config->get('age_checker_option_remember_me'),
	    '#description' => t('If this checkbox is enabled, a remember me checkbox would be seen on the Age gate page.'),
	  );

	  // URL from where we need to get the Country code.
	  $form['age_checker_country_code_url'] = array(
	    '#title' => t('URL for fetching the country code'),
	    '#type' => 'textfield',
	    '#default_value' => $age_checker_config->get('age_checker_country_code_url'),
	    '#description' => t('API for fetching the country code.'),
	  );

	  // Language list for Age Gate.
	  $form['lang'] = array(
	    '#title' => t('Age Checker Language'),
	    '#type' => 'details',
	  );
	  $form['lang']['age_checker_language'] = array(
	    '#type' => 'textarea',
	    '#title' => t('Please enter the list of languages in key|value pair.'),
	    '#required' => TRUE,
	    '#maxlength' => 255,
	    '#default_value' => $age_checker_config->get('age_checker_language'),
	    '#description' => t('Please enter required language in key|value format for e.g. es|Español. The name of the key should not have any space.'),
	  );

	  // Country list for Age Gate.
	  $form['country'] = array(
	    '#title'         => t('Age Checker Countries'),
	    '#type'          => 'details',
	  );
	  $country_list = Url::fromUri('http://www.worldatlas.com/aatlas/ctycodes.htm');
		$country_code = \Drupal::l(t('http://www.worldatlas.com/aatlas/ctycodes.htm'), $country_list,
			array(
				'attributes' => array(
					'class' => 'sample_link'
				)
			)
		);
	  $form['country']['age_checker_countries'] = array(
	    '#type' => 'textarea',
	    '#title' => t('Please enter the list of countries in key|value pair.'),
	    '#required' => TRUE,
	    '#maxlength' => 255,
	    '#default_value' => $age_checker_config->get('age_checker_countries'),
	    '#description' => t('Please enter required country in localized language e.g. ES|España. The key should be picked up from A2 (ISO) column of ') . $country_code . t(' site depending on the value of the country.'),
	  );

	  // Verification options for age checker.
	  $form['options'] = array(
	    '#title' => t('Age Checker Options'),
	    '#type' => 'details',
	  );

	  // Cookie Expiration Time.
	  $form['options']['age_checker_cookie_expiration_time'] = array(
	    '#title' => t('Cookie expiration days'),
	    '#type' => 'textfield',
	    '#field_suffix' => t('Days'),
	    '#size' => 6,
	    '#element_validate' => array('element_validate_integer'),
	    '#default_value' => $age_checker_config->get('age_checker_cookie_expiration_time'),
	    '#description' => t('The number of days before the cookie set by age checker module expires, and the user must verify their age again (0 days will expire at end of session).'),
	  );

	  // Age Checker URL.
	  $form['options']['age_checker_under_age_url'] = array(
	    '#title' => t('Enter underage page url'),
	    '#type' => 'textfield',
	    '#default_value' => $age_checker_config->get('age_checker_under_age_url'),
	    '#required' => TRUE,
	    '#description' => t('Please add http:// or https:// for external url  or create a drupal CMS page and enter Drupal path for internal CMS Page. E.g "under-age" for  http://www.example.com/sitename/under-age'),
	  );

	  // Age Checker Visibility.
	  $form['options']['age_checker_visibility'] = array(
	    '#type' => 'radios',
	    '#title' => t('Show Age Gate on specific pages'),
	    '#options' => array(
	      AGE_CHECKER_VISIBILITY_NOTLISTED => t('Show on all pages except those listed'),
	      AGE_CHECKER_VISIBILITY_LISTED    => t('Show only on the listed pages'),
	    ),
	    '#default_value' => $age_checker_config->get('age_checker_visibility'),
	  );
	  // Age checker specific pages.
	  $form['options']['age_checker_pages'] = array(
	    '#type' => 'textarea',
	    '#title' => t('Age gate exception pages'),
	    '#default_value' => $age_checker_config->get('age_checker_pages'),
	    '#description' => t("Enter the path of the page e.g. enter 'blog' for the blog main page and 'blog/*' for the blog main page and its subpages."),
	  );

	  // Background Image for Age Gate.
	  $form['options']['age_checker_background_image'] = array(
	    '#type' => 'managed_file',
	    '#name' => 'backgroundimage_image',
	    '#title' => t('Add Background image'),
	    '#default_value' => array($age_checker_config->get('age_checker_background_image')),
	    '#description' => t("Upload an image for the background of Age Gate. Allowed Extensions for the Image are gif, png, jpg, jpeg"),
	    '#upload_location' => 'public://images_age_checker/',
	    '#upload_validators' => array(
	      'file_validate_extensions' => array('gif png jpg jpeg'),
	      'file_validate_size' => array(1000000),
	    ),
	  );

	  // Logo Image for Age Gate.
	  $form['options']['age_checker_logo'] = array(
	    '#type' => 'managed_file',
	    '#name' => 'logo_agechecker',
	    '#title' => t('Add logo image'),
	    '#default_value' => array($age_checker_config->get('age_checker_logo')),
	    '#description' => t("Upload an image for the logo of Age Gate. Allowed Extensions for the Image are gif, png, jpg, jpeg"),
	    '#upload_location' => 'public://images_age_checker/',
	    '#upload_validators' => array(
	      'file_validate_extensions' => array('gif png jpg jpeg'),
	      'file_validate_size' => array(1000000),
	    ),
	  );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  	// Get user values.
  	$user_values = $form_state->getValue();

    // Validation for countries.
	  $countries = $user_values['age_checker_countries'];
	  $countries = explode("\n", $countries);
	  $country_options = array();
	  foreach ($countries as $country) {
	    $country = explode('|', $country);
	    if ($country[1] == '') {
	    	$form_state->setErrorByName('age_checker_countries', $this->t('Please remove the extra space.'));
	    }
	    $country_options[$country[0]] = $country[1];
	    $countries_list = array_map('trim', $country_options);
	  }
	  foreach ($countries_list as $country_list) {
	    if (preg_match('/[0-9]/', $country_list)) {
	    	$form_state->setErrorByName('age_checker_countries', $this->t('Please enter proper country name.'));
	    }
	  }

	  // Validation for languages.
	  $languages = $user_values['age_checker_language'];
	  $languages = explode("\n", $languages);
	  foreach ($languages as $language) {
	    $language = explode('|', $language);
	    if ($language[1] == '') {
	    	$form_state->setErrorByName('age_checker_language', $this->t('Please remove the extra space.'));
	    }
	    $language_options[$language[0]] = $language[1];
	    $language_list = array_map('trim', $language_options);
	  }
	  foreach ($language_list as $language) {
	    if (preg_match('/[0-9]/', $language)) {
	    	$form_state->setErrorByName('age_checker_language', $this->t('Please enter proper language name.'));
	    }
	  }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  	$userInputValues = $form_state->getValue();
    $config = $this->configFactory->getEditable('age_checker.settings');
    $config->set('age_checker_country_code_url', $userInputValues['age_checker_country_code_url']);
    $config->set('age_checker_language', $userInputValues['age_checker_language']);
    $config->set('age_checker_countries', $userInputValues['age_checker_countries']);
    $config->set('age_checker_cookie_expiration_time', $userInputValues['age_checker_cookie_expiration_time']);
    $config->set('age_checker_under_age_url', $userInputValues['age_checker_under_age_url']);
    $config->set('age_checker_visibility', $userInputValues['age_checker_visibility']);
    $config->set('age_checker_pages', $userInputValues['age_checker_pages']);
    $config->set('age_checker_background_image', $userInputValues['age_checker_background_image'][0]);
		$config->set('age_checker_logo', $userInputValues['age_checker_logo'][0]);
    $config->save();

  	return parent::buildForm($form, $form_state);
  }

}
