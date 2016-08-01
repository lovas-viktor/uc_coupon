<?php

namespace Drupal\uc_coupon;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Coupon entities.
 *
 * @ingroup uc_coupon
 */
class CouponListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['coupon_code'] = $this->t('Coupon code');
    $header['discount'] = $this->t('Discount');
    $header['valid'] = $this->t('Valid');
    $header['status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\uc_coupon\Entity\Coupon */
    $row['coupon_code'] = $this->l(
      $entity->label(),
      new Url(
        'entity.coupon.edit_form', array(
          'coupon' => $entity->id(),
        )
      )
    );
    $row['discount'] = $entity->discount->value;
    if($entity->date_validation->value){
      $row['valid'] = format_date($entity->valid_from->value,'short').' -> '.format_date($entity->valid_to->value,'short');
    } else {
      $row['valid'] = t('No date validation');
    }

    if($entity->status->value){
      $row['status'] = $this->t('Active');
    } else {
      $row['status'] = $this->t('Inctive');
    }
    return $row + parent::buildRow($entity);
  }

}
