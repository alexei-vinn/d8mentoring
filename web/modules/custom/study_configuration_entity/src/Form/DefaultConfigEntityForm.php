<?php

namespace Drupal\study_configuration_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DefaultConfigEntityForm.
 */
class DefaultConfigEntityForm extends EntityForm {

  protected $messenger;

  public function __construct(MessengerInterface $messenger)
  {
    $this->messenger = $messenger;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $default_config_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $default_config_entity->label(),
      '#description' => $this->t("Label for the Default config entity."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $default_config_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\study_configuration_entity\Entity\DefaultConfigEntity::load',
      ],
      '#disabled' => !$default_config_entity->isNew(),
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 255,
      '#default_value' => $default_config_entity->get('title'),
      '#description' => $this->t("Title for the New configured entity.."),
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#maxlength' => 255,
      '#default_value' => $default_config_entity->get('description'),
      '#description' => $this->t("Description the new configured entity."),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $default_config_entity = $this->entity;
    $status = $default_config_entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Default config entity.', [
          '%label' => $default_config_entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Default config entity.', [
          '%label' => $default_config_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($default_config_entity->toUrl('collection'));
  }

}
