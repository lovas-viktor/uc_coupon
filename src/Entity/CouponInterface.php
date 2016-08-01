<?php

namespace Drupal\uc_coupon\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Coupon entities.
 *
 * @ingroup uc_coupon
 */
interface CouponInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Coupon name.
   *
   * @return string
   *   Name of the Coupon.
   */
  public function getName();

  /**
   * Sets the Coupon name.
   *
   * @param string $name
   *   The Coupon name.
   *
   * @return \Drupal\uc_coupon\Entity\CouponInterface
   *   The called Coupon entity.
   */
  public function setName($name);

  /**
   * Gets the Coupon creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Coupon.
   */
  public function getCreatedTime();

  /**
   * Sets the Coupon creation timestamp.
   *
   * @param int $timestamp
   *   The Coupon creation timestamp.
   *
   * @return \Drupal\uc_coupon\Entity\CouponInterface
   *   The called Coupon entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Coupon published status indicator.
   *
   * Unpublished Coupon are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Coupon is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Coupon.
   *
   * @param bool $published
   *   TRUE to set this Coupon to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\uc_coupon\Entity\CouponInterface
   *   The called Coupon entity.
   */
  public function setPublished($published);

}
