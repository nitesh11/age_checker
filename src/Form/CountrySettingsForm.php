<?php

/**
 * @file
 * Contains \Drupal\age_checker\Form\CountrySettingsForm.
 */

namespace Drupal\age_checker\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Edit config variable form.
 */
class CountrySettingsForm extends ConfigFormBase {

	/**
   * FormID of the form.
   */
	public function getFormId() {
    return 'age_checker_country_configuration';
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

  	$form = array();

	  global $base_url;
	  $country_options = array();
	  $countries = $age_checker_config->get('age_checker_countries');
	  $countries = explode("\n", $countries);
	  foreach ($countries as $country) {
	    $country_array = explode('|', $country);
	    $country_options[$country_array[0]] = $country_array[1];
	    $country_list = array_map('trim', $country_options);
	  }

	  $form['default_country_configuration'] = array(
	    '#title' => t('Default Country Configuration'),
	    '#type' => 'details',
	  );
	  $form['default_country_configuration']['age_checker_default_country'] = array(
	    '#type' => 'select',
	    '#description' => t('Select the default country of the site.'),
	    '#default_value' => $age_checker_config->get('age_checker_default_country'),
	    '#options' => $country_list,
	  );

	  $form['ages'] = array(
	    '#title' => t('Threshold ages of the country'),
	    '#type' => 'details',
	  );

	  // Minimum Age Checker.
	  foreach ($countries as $country) {
	    $country_array = explode('|', $country);
	    $country_array = array_map('trim', $country_array);
	    $form['ages']['age_checker_' . $country_array[0] . '_threshold_ages'] = array(
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#title' => $country_array[1],
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_threshold_ages')) ? $age_checker_config->get('age_checker_threshold_ages') : $age_checker_config->get('age_checker_' . $country_array[0] . '_threshold_ages'),
	      '#type' => 'textfield',
	      '#size' => 3,
	      '#element_validate' => array('element_validate_integer'),
	    );
	  }

	  // Getting the format of the date field.
	  $form['country_specific'] = array(
	    '#title' => t('Country Specific Configuration'),
	    '#type' => 'details',
	  );

	  foreach ($countries as $country) {
	    $country_array = explode('|', $country);
	    $country_array = array_map('trim', $country_array);
	    $form['country_specific'][$country_array[0]] = array(
	      '#title' => $country_array[1],
	      '#type' => 'details',
	    );

	    // Link for Multilingual site.
	    if (\Drupal::moduleHandler()->moduleExists('locale')) {
	      // Fieldset for Changing the Date format.
	      $form['country_specific'][$country_array[0]]['multilingual'] = array(
	        '#title' => t('Redirect links after age gate.'),
	        '#type' => 'details',
	      );
	      // Changing the weight of Day field.
	      $form['country_specific'][$country_array[0]]['multilingual']['age_checker_' . $country_array[0] . '_redirect_link'] = array(
	        '#title' => t('Redirect Link'),
	        '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_redirect_link')) ? $base_url : $age_checker_config->get('age_checker_' . $country_array[0] . '_redirect_link'),
	        '#type' => 'textfield',
	        '#size' => 255,
	      );
	    }

	    // Fieldset for Changing the Date format.
	    $form['country_specific'][$country_array[0]]['weight'] = array(
	      '#title' => t('Changing the order of the date field'),
	      '#type' => 'details',
	    );
	    // Changing the weight of Day field.
	    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_day_weight'] = array(
	      '#title' => t('Weight of Day Field :'),
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_day_weight')) ? $age_checker_config->get('age_checker_day_weight') : $age_checker_config->get('age_checker_' . $country_array[0] . '_day_weight'),
	      '#type' => 'textfield',
	      '#size' => 1,
	      '#element_validate' => array('element_validate_integer'),
	    );
	    // Changing the weight of Month field.
	    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_month_weight'] = array(
	      '#title' => t('Weight of Month Field :'),
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_month_weight')) ? $age_checker_config->get('age_checker_month_weight') : $age_checker_config->get('age_checker_' . $country_array[0] . '_month_weight'),
	      '#type' => 'textfield',
	      '#size' => 1,
	      '#element_validate' => array('element_validate_integer'),
	    );
	    // Changing the weight of Year field.
	    $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_year_weight'] = array(
	      '#title' => t('Weight of Year field :'),
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_year_weight')) ? $age_checker_config->get('age_checker_year_weight') : $age_checker_config->get('age_checker_' . $country_array[0] . '_year_weight'),
	      '#type' => 'textfield',
	      '#size' => 1,
	      '#element_validate' => array('element_validate_integer'),
	    );

	    // Fieldset for Changing the Placeholder.
	    $form['country_specific'][$country_array[0]]['placeholder'] = array(
	      '#title' => t('Changing the placeholder of the date field'),
	      '#type' => 'details',
	      '#collapsible' => TRUE,
	      '#collapsed' => TRUE,
	    );
	    // Placeholder for Day format.
	    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_day_placeholder'] = array(
	      '#type' => 'textfield',
	      '#title' => t('Day Placeholder :'),
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#required' => FALSE,
	      '#size' => 2,
	      '#maxlength' => 2,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_day_placeholder')) ? $age_checker_config->get('age_checker_day_placeholder') : $age_checker_config->get('age_checker_' . $country_array[0] . '_day_placeholder'),
	    );
	    // Placeholder for Month format.
	    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_month_placeholder'] = array(
	      '#type' => 'textfield',
	      '#title' => t('Month Placeholder :'),
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#required' => FALSE,
	      '#size' => 2,
	      '#maxlength' => 2,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_month_placeholder')) ? $age_checker_config->get('age_checker_month_placeholder') : $age_checker_config->get('age_checker_' . $country_array[0] . '_month_placeholder'),
	    );
	    // Placeholder for Year format.
	    $form['country_specific'][$country_array[0]]['placeholder']['age_checker_' . $country_array[0] . '_year_placeholder'] = array(
	      '#type' => 'textfield',
	      '#prefix' => '<div class="container-inline">',
	      '#suffix' => '</div>',
	      '#title' => t('Year Placeholder :'),
	      '#required' => FALSE,
	      '#size' => 4,
	      '#maxlength' => 4,
	      '#default_value' => is_null($age_checker_config->get('age_checker_' . $country_array[0] . '_year_placeholder')) ? $age_checker_config->get('age_checker_year_placeholder') : $age_checker_config->get('age_checker_' . $country_array[0] . '_year_placeholder'),
	    );
	  }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  	$userInputValues = $form_state->getValue();

    $config = $this->configFactory->getEditable('age_checker.settings');

    $age_checker_config = $this->config('age_checker.settings');
	  $countries = $age_checker_config->get('age_checker_countries');
	  $countries = explode("\n", $countries);

	  foreach ($countries as $country) {
	    $country_array = explode('|', $country);
	    $country_array = array_map('trim', $country_array);
	    $country = $country_array[0];
	    $config->set('age_checker_' . $country_array[0] . '_threshold_ages', $userInputValues['age_checker_' . $country_array[0] . '_threshold_ages']);

	    // Link for Multilingual site.
	    if (\Drupal::moduleHandler()->moduleExists('locale')) {
	    	$config->set('age_checker_' . $country_array[0] . '_redirect_link', $userInputValues['age_checker_' . $country_array[0] . '_redirect_link']);
	    }

	    $config->set('age_checker_' . $country_array[0] . '_day_weight', $userInputValues['age_checker_' . $country_array[0] . '_day_weight']);
	    $config->set('age_checker_' . $country_array[0] . '_month_weight', $userInputValues['age_checker_' . $country_array[0] . '_month_weight']);
	    $config->set('age_checker_' . $country_array[0] . '_year_weight', $userInputValues['age_checker_' . $country_array[0] . '_year_weight']);
	    $config->set('age_checker_' . $country_array[0] . '_day_placeholder', $userInputValues['age_checker_' . $country_array[0] . '_day_placeholder']);
	    $config->set('age_checker_' . $country_array[0] . '_month_placeholder', $userInputValues['age_checker_' . $country_array[0] . '_month_placeholder']);
	    $config->set('age_checker_' . $country_array[0] . '_year_placeholder', $userInputValues['age_checker_' . $country_array[0] . '_year_placeholder']);
		}
    $config->save();

  	return parent::buildForm($form, $form_state);
  }
}
