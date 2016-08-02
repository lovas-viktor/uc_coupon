<?php

namespace Drupal\uc_coupon;

/**
 * Class CouponOrderService.
 *
 * @package Drupal\uc_coupon
 */
class CouponOrderService {

  /**
   * Constructor.
   */
  public function __construct() {

  }

  public function getCouponDiscountAmount($order,$coupon_code)
  {
    if($discount = $this->validateCoupon($order,$coupon_code)){
      if(strstr($discount, '%')){
        $price-=($order->getSubtotal()*((intval($discount)/100)));
      } else {
        $price-=$discount;
      }
      return $price;
    } else {
      return FALSE;
    }
  }

  public function setCouponDiscountOrder($order,$coupon_code)
  {
    $discount_amount=$this->getCouponDiscountAmount($order,$coupon_code);
    if($discount_amount){
      $coupon_line_item_data=$this->getOrderCouponLineItemData($order);
      if($coupon_line_item_data['coupon_code']!=$coupon_code){
        db_delete('uc_order_line_items')
          ->condition('order_id', $order->id())
          ->condition('type', 'uc_coupon')
          ->execute();
        $data['coupon_code']=$coupon_code;
        $data['timestamp']=time();
        uc_order_line_item_add($order->id(), 'uc_coupon', 'Coupon', $discount_amount, 0, $data);
      }
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function validateCoupon($order,$coupon_code)
  {
    $query = \Drupal::entityQuery('coupon')
      ->condition('status', 1)
      ->condition('coupon_code', $coupon_code, '=');
    $cids = $query->execute();

    if(!$cids){
      return FALSE;
    }
    $coupon=entity_load('coupon', reset($cids));

    return $coupon->discount->value;
  }

  public function getOrderCouponLineItemData($order)
  {
    $result = db_select('uc_order_line_items', 'l')
      ->fields('l', array('data'))
      ->condition('l.order_id', $order->id())
      ->condition('l.type', 'uc_coupon')
      ->range(0, 1)
      ->execute()
      ->fetchAssoc();
    $data=unserialize($result['data']);
    return $data;
  }
}
