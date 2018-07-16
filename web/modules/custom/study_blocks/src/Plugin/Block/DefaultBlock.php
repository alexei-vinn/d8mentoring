<?php

namespace Drupal\study_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Default block"),
 * )
 */
class DefaultBlock extends BlockBase implements ContainerFactoryPluginInterface {


  protected $MyEntityStorage;
  protected $NodeStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityStorageInterface $entity_storage, EntityStorageInterface $node_storage)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->MyEntityStorage = $entity_storage;
    $this->NodeStorage = $node_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    $storage = $container->get('entity.manager');
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $storage->getStorage('default_entity'),
      $storage->getStorage('node'));
  }


  /**
   * {@inheritdoc}
   */
  public function build() {

    $ids = $this->MyEntityStorage->getQuery()
      ->condition('status', 1)
      ->execute();
    $entities = $this->MyEntityStorage->loadMultiple($ids);
    $items = array();
    $build = [];
    foreach ($entities as $index=>$entity) {
      if ($entity->hasField('field_referenced_article') && $entity->getFieldDefinition('field_referenced_article')->getType() == 'entity_reference') {
        if (!empty($entity->get('field_referenced_article')->getValue()[0]['target_id']) && $referenced = $this->NodeStorage->load($entity->get('field_referenced_article')->getValue()[0]['target_id'])) {

          if ($referenced->hasField('field_color') && !empty($referenced->get('field_color')->getValue()[0]['value'])) {

            $items['name'][$index] = $entity->get('name')->getValue()[0]['value'];
            $items['color'][$index] = $entity->get('field_referenced_article')->referencedEntities()[0]->get('field_color')->getValue()[0]['value'];
            }

        }

      }

    }
    $build['default_block'] = array(
      '#theme' => 'item_list',
      '#items' => $items['name']
    );
    $build['#attached']['library'][] = 'study_blocks/study_blocks.colored';
    $build['#attached']['drupalSettings']['study_blocks']['colors'] = $items['color'];
    $build['#attached']['drupalSettings']['study_blocks']['classname'] = $this->getPluginId();
    $build['#attributes']['class'][] = $this->getPluginId();
    $build['#cache']['max-age'] = 0;
    return $build;
  }
}
