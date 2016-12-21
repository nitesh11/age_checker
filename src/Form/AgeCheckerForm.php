<?php
/**
 * @file
 * Contains Drupal\age_checker\Form\AgeChecker.
 */

namespace Drupal\age_checker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\age_checker\Controller\AgeCheckerAgeGate;

/**
 * Implements an AgeChecker form.
 */
class AgeCheckerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'age_checker_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = array();
    // Getting the langauge_code.
    $language_code = AgeCheckerAgeGate::age_checker_get_language_code();

    // Default Country.
    $selected_country = isset($_COOKIE['country_selected']) ? $_COOKIE['country_selected'] : age_checker_get_country_name();

    // Country list.
    $countries = \Drupal::state()->get('age_checker_countries') ? (\Drupal::state()->get('age_checker_countries')) : \Drupal::config('age_checker.settings')->get('age_checker_countries');
    $countries = explode("\n", $countries);
    foreach ($countries as $country) {
      $country = explode('|', $country);
      $country = array_map('trim', $country);
      $result[$country[0]] = $country[1];
    }
    if (count($result) > 1) {
      $form['list_of_countries'] = array(
        '#type' => 'select',
        '#title' => \Drupal::state()->get('age_checker_' . $language_code . '_select_list_label'),
        '#options' => $result,
        '#weight' => -1,
        '#id' => 'age_checker_country',
        '#default_value' => $selected_country,
        '#attributes' => array(
          'tabindex' => '1',
        ),
      );
    }

    $form['age_checker_error_message'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="age_checker_error_message"> </div>',
      '#weight' => 0,
    );

    // Day form Element.
    $form['day'] = array(
      '#type' => 'textfield',
      '#size' => 2,
      '#maxlength' => 2,
      '#id' => 'age_checker_day',
      '#weight' => \Drupal::state()->get('age_checker_' . $selected_country . '_day_weight'),
      '#required' => TRUE,
      '#attributes' => array(
        'pattern' => "[0-9]*",
        'tabindex' => \Drupal::state()->get('age_checker_' . $selected_country . '_day_weight'),
        'placeholder' => \Drupal::state()->get('age_checker_' . $selected_country . '_day_placeholder'),
      ),
    );

    // Month form Element.
    $form['month'] = array(
      '#type' => 'textfield',
      '#size' => 2,
      '#maxlength' => 2,
      '#id' => 'age_checker_month',
      '#required' => TRUE,
      '#weight' => \Drupal::state()->get('age_checker_' . $selected_country . '_month_weight'),
      '#attributes' => array(
        'pattern' => "[0-9]*",
        'tabindex' => \Drupal::state()->get('age_checker_' . $selected_country . '_month_weight'),
        'placeholder' => \Drupal::state()->get('age_checker_' . $selected_country . '_month_placeholder'),
      ),
    );

    // Year form Element.
    $form['year'] = array(
      '#type' => 'textfield',
      '#size' => 4,
      '#maxlength' => 4,
      '#id' => 'age_checker_year',
      '#weight' => \Drupal::state()->get('age_checker_' . $selected_country . '_year_weight'),
      '#required' => TRUE,
      '#attributes' => array(
        'pattern' => "[0-9]*",
        'tabindex' => \Drupal::state()->get('age_checker_' . $selected_country . '_year_weight'),
        'placeholder' => \Drupal::state()->get('age_checker_' . $selected_country . '_year_placeholder'),
      ),
    );

    // Remember Me Checkbox.
    $option_remember_me = \Drupal::state()->get('age_checker_option_remember_me');
    if ($option_remember_me == 1) {
      $form['remember_me'] = array(
        '#type' => 'checkbox',
        '#weight' => 5,
        '#id' => 'age_checker_remember_me',
        '#title' => \Drupal::state()->get('age_checker_' . $language_code . '_remember_me_text'),
        '#default_value' => 0,
        '#attributes' => array(
          'tabindex' => '5',
        ),
      );
    }

    // Submit button.
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => \Drupal::state()->get('age_checker_' . $language_code . '_button_text'),
      '#weight' => 6,
      '#attributes' => array(
        'onclick' => "age_checker.verify();",
        'tabindex' => '6',
      ),
    );

    $form['#attributes']['onsubmit'] = 'return false;';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
