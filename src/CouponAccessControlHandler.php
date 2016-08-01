<?php

namespace Drupal\uc_coupon;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Coupon entity.
 *
 * @see \Drupal\uc_coupon\Entity\Coupon.
 */
class CouponAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\uc_coupon\Entity\CouponInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished coupon entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published coupon entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit coupon entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete coupon entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add coupon entities');
  }

}
