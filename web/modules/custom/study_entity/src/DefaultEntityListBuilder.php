<?php

namespace Drupal\study_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Default entity entities.
 *
 * @ingroup study_entity
 */
class DefaultEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Default entity ID');
    $header['name'] = $this->t('Name');
    $header['language'] = $this->t('Language');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\study_entity\Entity\DefaultEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.default_entity.edit_form',
      ['default_entity' => $entity->id()]
    );
    $row['language'] = $entity->language()->getName();
    return $row + parent::buildRow($entity);
  }

  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * @return array
   *   An array of entity IDs.
   */
  protected function getEntityIds() {
    $query = $this->getStorage()->getQuery()
      ->sort($this->entityType->getKey('id'));
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query->condition('langcode', $langcode);
    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }


}
