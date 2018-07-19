<?php

namespace Drupal\field_api\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'default_field' field type.
 *
 * @FieldType(
 *   id = "default_field",
 *   label = @Translation("Default field"),
 *   description = @Translation("My Field Type"),
 *   default_widget = "default_widget"
 * )
 */
class DefaultField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
  /*  $properties['value'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Text value'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);*/
    $properties['start_date'] = DataDefinition::create('timestamp')
      ->setLabel(new TranslatableMarkup(('Start date')))
    ->setRequired(TRUE);
    $properties['end_date'] = DataDefinition::create('timestamp')
      ->setLabel(new TranslatableMarkup(('End date')))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
       'start_date' => [
         'description' => 'Start date description',
         'type' => 'int'
       ],
        'end_date' => [
          'description' => 'End date description',
          'type' => 'int'
        ]
      ],
      'indexes' => []
    ];
    return $schema;
  }

  /**
   * Define when the field type is empty.
   *
   * This method is important and used internally by Drupal. Take a moment
   * to define when the field fype must be considered empty.
   */
  public function isEmpty() {

    $isEmpty =
      empty($this->get('start_date')->getValue()) &&
      empty($this->get('end_date')->getValue());

    return $isEmpty;
  }


  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['default_field'] = BaseFieldDefinition::create('default_field')
      ->setLabel(t('Default field label'))
      ->setDescription(t('Default field description'));

    return $fields;
  }

}
