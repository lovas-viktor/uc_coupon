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
    );*/
    $build['form']= \Drupal::formBuilder()->getForm('\Drupal\uc_coupon\Form\CouponCheckoutForm');
    $coupon_order = new CouponOrderService();
    $build['test'] = array(
      '#markup' => $coupon_order->getCouponDiscount($order->id()),
    );

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function review(OrderInterface $order) {
    $review[] = array(
      '#theme' => 'uc_cart_review_table',
      '#items' => $order->products,
      '#show_subtotal' => FALSE,
    );
    return $review;
  }
}
