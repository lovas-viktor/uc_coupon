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
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\uc_store\Ajax\CommandWrapper;

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

    $build['#attached']['library'][] = 'uc_coupon/uc_coupon';

    $build['coupon_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#maxlength' => 50,
      '#size' => 64,
      '#weight' => 1,
    ];

    $build['coupon_button'] = [
      '#type' => 'button',
      '#value' => t('Validate coupon'),
      '#limit_validation_errors' => array(),
      '#weight' => 2,
    ];

    $build['coupon_code_message'] = [
      '#markup' => '<span id="couponAjaxValidate"></span>',
      '#weight' => 3,
    ];

    $form_state->set(['uc_ajax', 'uc_coupon', 'panes][uc_coupon][coupon_button'], array(
      'Drupal\uc_coupon\Plugin\Ubercart\CheckoutPane\CouponPane::ajaxValidate',
      'payment-pane' => '::ajaxReplaceCheckoutPane',
    ));

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order, array $form, FormStateInterface $form_state) {
    $values=$form_state->getValues();
    $coupon_order = new CouponOrderService();
    $coupon_order->setCouponDiscountOrder($order,$values['panes']['uc_coupon']['coupon_code']);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function review(OrderInterface $order) {
    return NULL;
  }

  /**
   * Pane submission handler to trigger quote calculation.
   */
  public function paneSubmit($form, FormStateInterface $form_state) {
    $values=$form_state->getValues();
    $coupon_order = new CouponOrderService();
    if(!$coupon_order->setCouponDiscountOrder($order,$values['panes']['uc_coupon']['coupon_code'])){
      $form_state->setErrorByName('panes][uc_coupon][coupon_code', $this->t('Coupon code is not valid.'));
      $form_state->setRebuild();
      return FALSE;
    }
    $form_state->setErrorByName('panes][uc_coupon][coupon_code', $this->t('Coupon code is not valid.'));
    $form_state->setRebuild();
    return TRUE;

  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxValidate(array $form, FormStateInterface $form_state) {
    $values=$form_state->getValues();
    $coupon_order = new CouponOrderService();
    if($discount=$coupon_order->validateCoupon($values['panes']['uc_coupon']['coupon_code'])) {
      $css = ['border' => '1px solid green'];
      $message = '<p class="coupon_accepted" style="color:green;">'.t('Coupon accepted.').' ('.$discount.')</p>';
    }else {
      $css = ['border' => '1px solid red'];
      $message = '<p class="coupon_error" style="color:red;">'.t('Coupon is not valid.').'</p>';
    }

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#couponAjaxValidate', $message));
    $response->addCommand(new CssCommand('#edit-panes-uc-coupon-coupon-code', $css));
    return $response;
  }
}
