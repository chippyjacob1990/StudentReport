<?php

namespace Drupal\result\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\UserInterface;

/**
 * Defines the result entity class.
 *
 * @ContentEntityType(
 *   id = "result",
 *   label = @Translation("Result"),
 *   label_collection = @Translation("Results"),
 *   handlers = {
 *     "view_builder" = "Drupal\result\ResultViewBuilder",
 *     "list_builder" = "Drupal\result\ResultListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\result\Form\ResultForm",
 *       "edit" = "Drupal\result\Form\ResultForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "result",
 *   admin_permission = "administer result",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/result/add",
 *     "canonical" = "/result/{result}",
 *     "edit-form" = "/admin/content/result/{result}/edit",
 *     "delete-form" = "/admin/content/result/{result}/delete",
 *     "collection" = "/admin/content/result"
 *   },
 *   field_ui_base_route = "entity.result.settings"
 * )
 */
class Result extends ContentEntityBase implements ContentEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new result entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getRollNo() {
    return $this->get('roll_no')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRollNo($rollno) {
    return $this->set('roll_no', $rollno);
  }

  /**
   * {@inheritdoc}
   */
  public function getSubject() {
    return $this->get('subject')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSubject($name) {
    $this->set('subject', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getScore() {
    return $this->get('score')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setScore($name) {
    $this->set('score', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    return (bool) $this->get('status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStatus($status) {
    $this->set('status', $status);
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
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['roll_no'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t("Rollno"))
      ->setDescription(t("Student's rollno"))
      ->setSetting('target_type', 'student')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['subject'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Subject'))
      ->setDescription(t('Subject.'))
      ->setSettings([
        'allowed_values' => [
          'Math' => 'Math',
          'English' => 'English',
          'Geo' => 'Geo',
          'Hindi' => 'Hindi',
          'Biology' => 'Biology',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_default',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['score'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Score'))
      ->setDescription(t("Student's score"))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'integer',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setDescription(t('The user ID of the result author.'))
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the result was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the result was last edited.'));

    return $fields;
  }

}
