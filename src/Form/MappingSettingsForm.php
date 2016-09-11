<?php

/**
 * @file
 * Contains \Drupal\age_checker\Form\MappingSettingsForm.
 */

namespace Drupal\age_checker\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\SafeMarkup;

/**
 * Edit config variable form.
 */
class MappingSettingsForm extends ConfigFormBase {

	/**
   * FormID of the form.
   */
	public function getFormId() {
    return 'age_checker_mapping_admin_form';
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

	  $languages = $age_checker_config->get('age_checker_language');
	  $languages = explode("\n", $languages);
	  foreach ($languages as $language) {
	    $language = explode('|', $language);
	    $language = array_map('trim', $language);
	    $form[$language[0] . '_mapping'] = array(
	      '#type' => 'details',
	      '#title' => SafeMarkup::checkPlain($language[1]),
	    );
	    $language = $language[0];

	    // Field for selecting the country for a particular language.
	    $countries = $age_checker_config->get('age_checker_countries');
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
	      '#type' => 'textfield',
	      '#title' => SafeMarkup::checkPlain(t('Label for selecting country')),
	      '#maxlength' => 255,
	      '#required' => FALSE,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_select_list_label')) ? $age_checker_config->get('age_checker_select_list_label') : $age_checker_config->get('age_checker_' . $language . '_select_list_label'),
	    );

	    $form[$language . '_mapping']['age_checker_' . $language . '_country_list'] = array(
	      '#title' => t('Select country(s)'),
	      '#type' => 'select',
	      '#description' => t('Please select the country(s) to map to this language'),
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_country_list')) ? $age_checker_config->get('age_checker_country_list') : $age_checker_config->get('age_checker_' . $language . '_country_list'),
	      '#options' => $result,
	      '#multiple' => TRUE,
	    );

	    // Message to be added before the form.
	    $message_beforeform = $age_checker_config->get('age_checker_' . $language . '_age_gate_header', array('value' => '', 'format' => NULL));
	    $form[$language . '_mapping']['age_checker_' . $language . '_age_gate_header'] = array(
	      '#title' => t('Header text for the form'),
	      '#type' => 'text_format',
	      '#rows' => 6,
	      '#default_value' => isset($message_beforeform['value']) ? $message_beforeform['value'] : '',
	      '#description' => t('e.g. You must be of legal drinking age to enter this site. Enter you date of birth below.'),
	      '#format' => $message_beforeform['format'],
	    );

	    // Message to be added after the form.
	    $message_afterform = $age_checker_config->get('age_checker_' . $language . '_age_gate_footer', array('value' => '', 'format' => NULL));
	    $form[$language . '_mapping']['age_checker_' . $language . '_age_gate_footer'] = array(
	      '#title' => t('Cookie statement'),
	      '#type' => 'text_format',
	      '#rows' => 6,
	      '#default_value' => isset($message_afterform['value']) ? $message_afterform['value'] : '',
	      '#description' => t('e.g This website uses cookies that are stored on your computer in order to enhance your experience. By providing your date of birth, you also agree to the use of cookies.'),
	      '#format' => $message_afterform['format'],
	    );

	    // Age checker validation message.
	    $form[$language . '_mapping']['age_checker_' . $language . '_blank_error_msg'] = array(
	      '#type' => 'textarea',
	      '#title' => t('Blank Error Message'),
	      '#required' => TRUE,
	      '#maxlength' => 255,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_blank_error_msg')) ? $age_checker_config->get('age_checker_blank_error_msg') : $age_checker_config->get('age_checker_' . $language . '_blank_error_msg'),
	      '#description' => t('Enter a helpful and user-friendly message.'),
	    );

	    // Incorrect Date Format Validation Message.
	    $form[$language . '_mapping']['age_checker_' . $language . '_dateformat_error_msg'] = array(
	      '#type' => 'textarea',
	      '#title' => t('Incorrect Date Format Message'),
	      '#required' => TRUE,
	      '#maxlength' => 255,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_dateformat_error_msg')) ? $age_checker_config->get('age_checker_dateformat_error_msg') : $age_checker_config->get('age_checker_' . $language . '_dateformat_error_msg'),
	      '#description' => t('Enter a helpful and user-friendly message.'),
	    );
	    // Date Out of Range Validation Message.
	    $form[$language . '_mapping']['age_checker_' . $language . '_daterange_error_msg'] = array(
	      '#type' => 'textarea',
	      '#title' => t('Date Out Of Range Message'),
	      '#required' => TRUE,
	      '#maxlength' => 255,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_daterange_error_msg')) ? $age_checker_config->get('age_checker_daterange_error_msg') : $age_checker_config->get('age_checker_' . $language . '_daterange_error_msg'),
	      '#description' => t('Enter a helpful and user-friendly message.'),
	    );

	    // Error message for under age.
	    $form[$language . '_mapping']['age_checker_' . $language . '_underage_error_msg'] = array(
	      '#type' => 'textarea',
	      '#title' => t('Under Age Validation Message'),
	      '#required' => TRUE,
	      '#maxlength' => 255,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_underage_error_msg')) ? $age_checker_config->get('age_checker_underage_error_msg') : $age_checker_config->get('age_checker_' . $language . '_underage_error_msg'),
	      '#description' => t('Enter a helpful and user-friendly message.'),
	    );

	    // Remember Me Text Configuration.
	    $form[$language . '_mapping']['age_checker_' . $language . '_remember_me_text'] = array(
	      '#type' => 'textfield',
	      '#title' => t('Remember Me Text'),
	      '#maxlength' => 255,
	      '#required' => FALSE,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_remember_me_text')) ? $age_checker_config->get('age_checker_remember_me_text') : $age_checker_config->get('age_checker_' . $language . '_remember_me_text'),
	      '#description' => t('Please enter the remember me text.'),
	    );

	    // Age checker submit button text.
	    $form[$language . '_mapping']['age_checker_' . $language . '_button_text'] = array(
	      '#type' => 'textfield',
	      '#title' => t('Label of submit button'),
	      '#maxlength' => 50,
	      '#required' => TRUE,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $language . '_button_text')) ? $age_checker_config->get('age_checker_button_text') : $age_checker_config->get('age_checker_' . $language . '_button_text'),
	      '#description' => t('Please enter submit button text.'),
	    );

	    // Configuration for adding Footer Links.
	    $form[$language . '_mapping']['age_checker_' . $language . '_footer_links'] = array(
	      '#type' => 'textarea',
	      '#title' => t('Footer text and link'),
	      '#required' => FALSE,
	      '#maxlength' => 255,
	      '#default_value' => $age_checker_config->get('age_checker_' . $language . '_footer_links'),
	      '#description' => t('Please enter footer links like Privacy policy, Terms of use, cookie policy in key|value format for e.g - Privacy Policy | http://site.link'),
	    );

	    // Copy right text.
	    $copyright_text = $age_checker_config->get('age_checker_' . $language . '_copyright');
	    $form[$language . '_mapping']['age_checker_' . $language . '_copyright'] = array(
	      '#type' => 'text_format',
	      '#title' => t('Copyright text'),
	      '#description' => t('This field is used for the footer copyright text.'),
	      '#rows' => 6,
	      '#default_value' => isset($copyright_text['value']) ? $copyright_text['value'] : '',
	      '#description' => t('Copyright © 2015 Brand name. All rights reserved'),
	      '#format' => $copyright_text['format'],
	    );
	  }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  	// Get user values.
  	$user_values = $form_state->getValue();

  	$age_checker_config = $this->config('age_checker.settings');

    // Get the countries array.
	  $countries = $age_checker_config->get('age_checker_countries');
	  $countries = explode("\n", $countries);
	  foreach ($countries as $country) {
	    $country = explode('|', $country);
	    $countries_array[] = trim($country[0]);
	    $result = array_map('trim', $countries_array);
	  }

	  $languages = $age_checker_config->get('age_checker_language');
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
	    if (count($countries_array) == 0) {
	      $flag = 1;
	      $field_name = 'age_checker_' . $lang . '_country_list';
	      break;
	    }
	    else {
	      $countries_array = array_diff($countries_array, $user_values['age_checker_' . $lang . '_country_list']);
	    }
	    $count++;
	  }
	  if ($flag == 1) {
	    $form_state->setErrorByName($field_name, t('Language Mapping is not proper. You have mapped one country with more than one language.'));
	  }

	  if (count($countries_array) > 0) {
	    drupal_set_message(t('The mapping of few of the countries are missing.'), 'error');
	  }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  	$userInputValues = $form_state->getValue();

  	$age_checker_config = $this->config('age_checker.settings');
  	$config = $this->configFactory->getEditable('age_checker.settings');

	  $languages = $age_checker_config->get('age_checker_language');
	  $languages = explode("\n", $languages);
  	foreach ($languages as $language) {
	    $language = explode('|', $language);
	    $language = array_map('trim', $language);
	    $language = $language[0];

	    $config->set('age_checker_' . $language . '_select_list_label', $userInputValues['age_checker_' . $language . '_select_list_label']);
	    $config->set('age_checker_' . $language . '_country_list', $userInputValues['age_checker_' . $language . '_country_list']);
	    $config->set('age_checker_' . $language . '_age_gate_header', $userInputValues['age_checker_' . $language . '_age_gate_header']);
	    $config->set('age_checker_' . $language . '_age_gate_footer', $userInputValues['age_checker_' . $language . '_age_gate_footer']);
	    $config->set('age_checker_' . $language . '_blank_error_msg', $userInputValues['age_checker_' . $language . '_blank_error_msg']);
	    $config->set('age_checker_' . $language . '_dateformat_error_msg', $userInputValues['age_checker_' . $language . '_dateformat_error_msg']);
	    $config->set('age_checker_' . $language . '_daterange_error_msg', $userInputValues['age_checker_' . $language . '_daterange_error_msg']);
	    $config->set('age_checker_' . $language . '_underage_error_msg', $userInputValues['age_checker_' . $language . '_underage_error_msg']);
	    $config->set('age_checker_' . $language . '_remember_me_text', $userInputValues['age_checker_' . $language . '_remember_me_text']);
	    $config->set('age_checker_' . $language . '_button_text', $userInputValues['age_checker_' . $language . '_button_text']);
			$config->set('age_checker_' . $language . '_footer_links', $userInputValues['age_checker_' . $language . '_footer_links']);
	 		$config->set('age_checker_' . $language . '_copyright', $userInputValues['age_checker_' . $language . '_copyright']);
		}
    $config->save();

  	return parent::buildForm($form, $form_state);
  }

}
