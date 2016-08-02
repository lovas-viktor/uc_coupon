<?php

/**
 * @file
 * Contains \Drupal\uc_coupon\Plugin\Ubercart\CheckoutPane\CouponPane.
 */

namespace Drupal\uc_coupon\Plugin\Ubercart\CheckoutPane;

use Drupal\Core\Form\FormStateInterface;
use Drupal\uc_cart\CheckoutPanePluginBase;
use Drupal\uc_order\OrderInterface;
use Drupal\uc_coupon\CouponOrderService;

/**
 * Displays the coupon form during checkout.
 *
 * @CheckoutPane(
 *   id = "uc_coupon",
 *   title = @Translation("Coupon"),
 *   weight = 1,
 * )
 */
class CouponPane extends CheckoutPanePluginBase {

  /**
   * {@inheritdoc}
   */
  public function view(OrderInterface $order, array $form, FormStateInterface $form_state) {
    $pane = $this->pluginDefinition['id'];
    /*$build['#description'] = $this->t('Use coupon code.');

    if ($order->id()) {
      $default = db_query('SELECT coupon_id FROM {uc_coupon_order} WHERE order_id = :id', [':id' => $order->id()])->fetchField();
    }
    else {
      $default = NULL;
    }
    $build['coupon_code'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#default_value' => $default,
    );
    $build=
    $coupon_order = new CouponOrderService();
    $build['test'] = array(
      '#markup' => $coupon_order->getCouponDiscount($order),
    );*/
    $build['coupon_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#maxlength' => 50,
      '#size' => 64,
    ];

    $build['coupon_button'] = [
      '#type' => 'button',
      '#value' => t('Submit'),
      '#ajax' => [
        'callback' => array($this, 'ajaxSubmit'),
        'event' => 'click',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Verifying coupon...'),
        ),
      ],
      '#suffix' => '<span class="email-valid-message"></span>'
    ];

    $form_state->set(['uc_ajax', 'uc_coupon', 'panes][uc_coupon][coupon_button'], array(
      'payment-pane' => 'uc_ajax_replace_checkout_pane',
    ));

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order, array $form, FormStateInterface $form_state) {
    $values=$form_state->getValues();
    $coupon_order = new CouponOrderService();
    if(!$coupon_order->setCouponDiscountOrder($order,$values['panes']['uc_coupon']['coupon_code'])){
      $form_state->setErrorByName('panes][uc_coupon][coupon_code', $this->t('Coupon code is not valid.'));
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function review(OrderInterface $order) {
    return NULL;
  }

}
