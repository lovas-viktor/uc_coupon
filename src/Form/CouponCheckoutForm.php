<?php

namespace Drupal\uc_coupon\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CouponCheckoutForm.
 *
 * @package Drupal\uc_coupon\Form
 */
class CouponCheckoutForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'coupon_checkout_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['coupon_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#maxlength' => 50,
      '#size' => 64,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#title' => $this->t('Submit'),
    ];

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Submit'),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
        drupal_set_message($key . ': ' . $value);
    }

  }

}
