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
 *   default_widget = "default_widget",
 *   default_formatter = "default_formatter"
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
       /* 'value' => [
          'type' => $field_definition->getSetting('is_ascii') === TRUE ? 'varchar_ascii' : 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
          'binary' => $field_definition->getSetting('case_sensitive'),
        ],*/
       'start_date' => [
         'description' => 'Start date description',
         'type' => 'int'
       ],
        'end_date' => [
          'description' => 'End date description',
          'type' => 'int'
        ]
      ],
    ];
    return $schema;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['default_field'] = BaseFieldDefinition::create('default_field')
      ->setLabel(t('Default field label'))
      ->setDescription(t('Default field description'));

    return $fields;
  }

}
