<?php

namespace Drupal\uc_coupon\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Coupon entity.
 *
 * @ingroup uc_coupon
 *
 * @ContentEntityType(
 *   id = "coupon",
 *   label = @Translation("Coupon"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\uc_coupon\CouponListBuilder",
 *     "views_data" = "Drupal\uc_coupon\Entity\CouponViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\uc_coupon\Form\CouponForm",
 *       "add" = "Drupal\uc_coupon\Form\CouponForm",
 *       "edit" = "Drupal\uc_coupon\Form\CouponForm",
 *       "delete" = "Drupal\uc_coupon\Form\CouponDeleteForm",
 *     },
 *     "access" = "Drupal\uc_coupon\CouponAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\uc_coupon\CouponHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "uc_coupon",
 *   admin_permission = "administer coupon entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "coupon_code",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/store/coupon/{coupon}",
 *     "add-form" = "/admin/store/coupon/add",
 *     "edit-form" = "/admin/store/coupon/{coupon}/edit",
 *     "delete-form" = "/admin/store/coupon/{coupon}/delete",
 *     "collection" = "/admin/store/coupon",
 *   },
 *   field_ui_base_route = "coupon.settings"
 * )
 */
class Coupon extends ContentEntityBase implements CouponInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Coupon entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE);

    $fields['coupon_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Coupon code'))
      ->setDescription(t('The Coupon code.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['discount'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Discount'))
      ->setDescription(t('The discount.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['date_validation'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Date validation'))
      ->setDescription(t('Date validation.'))
      ->setDisplayOptions('view', [
        'settings' => [
          'format' => 'unicode-yes-no',
        ],
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'settings' => [
          'display_label' => TRUE,
        ],
        'type' => 'boolean_checkbox',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['valid_from'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Valid from'))
      ->setDescription(t('Valid from.'))
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 0,

      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['valid_to'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Valid to'))
      ->setDescription(t('Valid to.'))
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => -0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Active'))
      ->setDescription(t('A boolean indicating whether the Coupon is active.'))
      ->setDisplayOptions('view', [
        'settings' => [
          'format' => 'unicode-yes-no',
        ],
        'weight' => 50,
      ])
      ->setDisplayOptions('form', [
        'settings' => [
          'display_label' => TRUE,
        ],
        'type' => 'boolean_checkbox',
        'weight' => 50,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
