<?php

namespace Drupal\field_api\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'entity_user_access_w' widget.
 *
 * @FieldWidget(
 *   id = "entity_user_access_w",
 *   label = @Translation("Entity User Access - Widget"),
 *   description = @Translation("Entity User Access - Widget"),
 *   field_types = {
 *     "entity_user_access",
 *   },
 *   multiple_values = TRUE,
 * )
 */

class EntityUserAccessWidget extends WidgetBase {

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['userlist'] = array(
      '#type' => 'select',
      '#title' => t('User'),
      '#description' => t('Select group members from the list.'),
      '#options' => array(
        0 => t('Anonymous'),
        1 => t('Admin'),
        2 => t('foobar'),
        // This should be implemented in a better way!
      ),
    );

    $element['passwordlist'] = array(
      '#type' => 'password',
      '#title' => t('Password'),
      '#description' => t('Select a password for the user'),
    );

    return $element;
  }

}
