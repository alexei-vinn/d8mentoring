<?php

namespace Drupal\field_api\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'default_widget' widget.
 *
 * @FieldWidget(
 *   id = "default_widget",
 *   label = @Translation("Default widget"),
 *   field_types = {
 *     "default_field"
 *   }
 * )
 */
class DefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['start_date'] = $element + [
      '#type' => 'textfield',
      '#title' => t('Start date'),
      '#default_value' => isset($items[$delta]->start_date) ? $items[$delta]->start_date : NULL,
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->t('Start date'),
      '#maxlength' => $this->getFieldSetting('max_length'),
    ];
    $element['end_date'] = $element + [
        '#type' => 'textfield',
        '#title' => t('End date'),
        '#default_value' => isset($items[$delta]->end_date) ? $items[$delta]->end_date : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->t('End date'),
        '#maxlength' => $this->getFieldSetting('max_length'),
      ];
    return $element;
  }

}
