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
  public function buildForm(array $form, FormStateInterface $form_state,$order = NULL) {
    $form['coupon_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#maxlength' => 50,
      '#size' => 64,
    ];

    $form['order_id'] = [
      '#type' => 'hidden',
      '#default_value' => $order->id(),
    ];

    $form['submit'] = [
        '#type' => 'button',
        '#value' => t('Submit'),
        '#ajax' => [
          'callback' => array($this, 'submitForm'),
          'event' => 'click',
          'progress' => array(
            'type' => 'throbber',
            'message' => t('Verifying email...'),
          ),
        ],
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
    db_delete('uc_order_line_items')
      ->condition('order_id', $form_state->getValue('order_id'))
      ->condition('type', 'uc_coupon')
      ->execute();
    uc_order_line_item_add($form_state->getValue('order_id'), 'uc_coupon', 'Coupon', 1-rand(10,100));

  }

}
