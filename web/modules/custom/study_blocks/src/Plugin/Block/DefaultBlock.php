<?php

namespace Drupal\study_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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

  protected $entity_type_manager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entity_type_manager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('entity_type.manager')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $ids = $this->entity_type_manager->getStorage('default_entity')->getQuery()->condition('status', 1)->execute();
    $entities = $this->entity_type_manager->getStorage('default_entity')->loadMultiple($ids);

    $items = array();
    $build = [];
    foreach ($entities as $index=>$entity) {
      if ($entity->hasField('field_referenced_article') && $entity->getFieldDefinition('field_referenced_article')->getType() == 'entity_reference') {
        if (!empty($entity->get('field_referenced_article')->getValue()[0]['target_id']) && $referenced = $this->entity_type_manager->getStorage('node')->load($entity->get('field_referenced_article')->getValue()[0]['target_id'])) {

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
