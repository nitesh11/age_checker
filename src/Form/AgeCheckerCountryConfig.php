<?php

/**
 * @file
 * Contains \Drupal\age_checker\Form\AgeCheckerSettingsForm.
 */

namespace Drupal\age_checker\Form;

use Drupal\Core\Datetime\Date;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AgeCheckerCountryConfig extends ConfigFormBase {
  public function getFormId() {
    return 'age_checker_country_settings';
  }
  public function getEditableConfigNames() {
    return [
      'age_checker_country.settings',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('age_checker_country.settings');

    global $base_url;
    $country_options = array();
    $countries = \Drupal::state()->get('age_checker_countries', '');
    $countries = explode("\n", $countries);
    foreach ($countries as $country) {
      if(isset($country)) {
        $country_array = explode('|', $country);
        if(isset($country_array[1])) {
          $country_options[$country_array[0]] = $country_array[1];
        }
      }
      $country_list = array_map('trim', $country_options);
    }
    $form['default_country_configuration'] = array(
      '#title' => t('Default Country Configuration'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['default_country_configuration']['age_checker_default_country'] = array(
      '#type' => 'select',
      '#description' => t('Select the default country of the site.'),
      '#default_value' => $config->get('age_checker_default_country'),
      '#options' => $country_list,
    );

    $form['ages'] = array(
      '#title' => t('Threshold ages of the country'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    // Minimum Age Checker.
    foreach ($countries as $country) {
      $country_array = explode('|', $country);
      $country_array = array_map('trim', $country_array);
      $form['ages']['age_checker_' . $country_array[0] . '_threshold_ages'] = array(
        '#prefix' => '<div class="container-inline">',
        '#suffix' => '</div>',
        '#title' => $country_array[1],
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_threshold_ages'),
        '#type' => 'textfield',
        '#size' => 3,
        '#element_validate' => array('element_validate_integer'),
      );
    }

    // Getting the format of the date field.
    $form['country_specific'] = array(
      '#title' => t('Country Specific Configuration'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    foreach ($countries as $country) {
      $country_array = explode('|', $country);
      $country_array = array_map('trim', $country_array);
      $form['country_specific'][$country_array[0]] = array(
        '#title' => $country_array[1],
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );

      // Link for Multilingual site.
      if (\Drupal::moduleHandler()->moduleExists('locale')) {
        // Fieldset for Changing the Date format.
        $form['country_specific'][$country_array[0]]['multilingual'] = array(
          '#title' => t('Redirect links after age gate.'),
          '#type' => 'fieldset',
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
        );
        // Changing the weight of Day field.
        $form['country_specific'][$country_array[0]]['multilingual']['age_checker_' . $country_array[0] . '_redirect_link'] = array(
          '#title' => t('Redirect Link'),
          '#default_value' => $config->get('age_checker_' . $country_array[0] . '_redirect_link', $base_url),
          '#type' => 'textfield',
          '#size' => 255,
        );
      }

      // Fieldset for Changing the Date format.
      $form['country_specific'][$country_array[0]]['weight'] = array(
        '#title' => t('Changing the order of the date field'),
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      // Changing the weight of Day field.
      $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_day_weight'] = array(
        '#title' => t('Weight of Day Field :'),
        '#prefix' => '<div class="container-inline">',
        '#suffix' => '</div>',
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_day_weight', 1),
        '#type' => 'textfield',
        '#size' => 1,
        '#element_validate' => array('element_validate_integer'),
      );
      // Changing the weight of Month field.
      $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_month_weight'] = array(
        '#title' => t('Weight of Month Field :'),
        '#prefix' => '<div class="container-inline">',
        '#suffix' => '</div>',
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_month_weight', 2),
        '#type' => 'textfield',
        '#size' => 1,
        '#element_validate' => array('element_validate_integer'),
      );
      // Changing the weight of Year field.
      $form['country_specific'][$country_array[0]]['weight']['age_checker_' . $country_array[0] . '_year_weight'] = array(
        '#title' => t('Weight of Year field :'),
        '#prefix' => '<div class="container-inline">',
        '#suffix' => '</div>',
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_year_weight', 3),
        '#type' => 'textfield',
        '#size' => 1,
        '#element_validate' => array('element_validate_integer'),
      );

      // Fieldset for Changing the Placeholder.
      $form['country_specific'][$country_array[0]]['placeholder'] = array(
        '#title' => t('Changing the placeholder of the date field'),
        '#type' => 'fieldset',
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
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_day_placeholder', 'DD'),
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
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_month_placeholder', 'MM'),
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
        '#default_value' => $config->get('age_checker_' . $country_array[0] . '_year_placeholder', 'YYYY'),
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements hook_form_submit().
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Set values in variables.

    $countries = \Drupal::state()->get('age_checker_countries', '');
    $countries = explode("\n", $countries);
    foreach ($countries as $country) {
      $country_array = explode('|', $country);
      $country_array = array_map('trim', $country_array);

      \Drupal::state()->set('age_checker_' . $country_array[0] . '_threshold_ages', $form_state->getValues()['age_checker_' . $country_array[0] . '_threshold_ages']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_redirect_link', $form_state->getValues()['age_checker_' . $country_array[0] . '_redirect_link']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_day_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_day_weight']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_month_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_month_weight']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_year_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_year_weight']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_day_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_day_placeholder']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_month_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_month_placeholder']);
      \Drupal::state()->set('age_checker_' . $country_array[0] . '_year_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_year_placeholder']);

      $this->config('age_checker_country.settings')
        ->set('age_checker_default_country', $form_state->getValues()['age_checker_default_country'])
        ->set('age_checker_' . $country_array[0] . '_threshold_ages', $form_state->getValues()['age_checker_' . $country_array[0] . '_threshold_ages'])
        ->set('age_checker_' . $country_array[0] . '_redirect_link', $form_state->getValues()['age_checker_' . $country_array[0] . '_redirect_link'])
        ->set('age_checker_' . $country_array[0] . '_day_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_day_weight'])
        ->set('age_checker_' . $country_array[0] . '_month_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_month_weight'])
        ->set('age_checker_' . $country_array[0] . '_year_weight', $form_state->getValues()['age_checker_' . $country_array[0] . '_year_weight'])
        ->set('age_checker_' . $country_array[0] . '_day_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_day_placeholder'])
        ->set('age_checker_' . $country_array[0] . '_month_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_month_placeholder'])
        ->set('age_checker_' . $country_array[0] . '_year_placeholder', $form_state->getValues()['age_checker_' . $country_array[0] . '_year_placeholder'])
        ->save();
    }
    parent::submitForm($form, $form_state);
  }
}
