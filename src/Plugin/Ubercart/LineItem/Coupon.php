<?php

/**
 * @file
 * Contains \Drupal\uc_coupon\Plugin\Ubercart\LineItem\Coupon.
 */

namespace Drupal\uc_coupon\Plugin\Ubercart\LineItem;

use Drupal\uc_order\LineItemPluginBase;
use Drupal\uc_order\OrderInterface;

/**
 * Handles the tax line item.
 *
 * @UbercartLineItem(
 *   id = "uc_coupon",
 *   title = @Translation("Coupon"),
 *   weight = 0,
 *   stored = TRUE,
 *   calculated = TRUE,
 *   add_list = TRUE
 * )
 */
class Coupon extends LineItemPluginBase {

  public function display(OrderInterface $order) {

  }

  public function load(OrderInterface $order) {

  }

}
