<?php

namespace Drupal\uc_coupon\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Coupon entities.
 */
class CouponViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['uc_coupon']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Coupon'),
      'help' => $this->t('The Coupon ID.'),
    );

    return $data;
  }

}
