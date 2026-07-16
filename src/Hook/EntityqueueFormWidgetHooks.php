<?php

namespace Drupal\entityqueue_form_widget\Hook;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Hook implementations for entityqueue_form_widget.
 */
class EntityqueueFormWidgetHooks {
  use StringTranslationTrait;

  /**
   * Constructs the hook implementations service.
   *
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(
    #[Autowire(service: 'current_user')]
    protected AccountInterface $currentUser,
    #[Autowire(service: 'entity_type.manager')]
    protected EntityTypeManagerInterface $entityTypeManager,
    #[Autowire(service: 'database')]
    protected Connection $database,
  ) {}

  /**
   * Implements hook_form_node_form_alter().
   */
  #[Hook('form_node_form_alter')]
  public function formNodeFormAlter(&$form, FormStateInterface $form_state) {
    $node = $form_state->getFormObject()->getEntity();
    $entity_id = $node->id();
    // Works with entityqueue module version 8.x-1.0-alpha6 .
    $allowed_entityqueues = $this->getAllowedSubqueueList($node);
    // Check if there is any entityqueues to show/not show the widget.
    if (!empty($allowed_entityqueues)) {
      $url = Url::fromRoute('entity.entity_queue.collection');
      $form['entityqueue_form_widget'] = [
        '#type' => 'details',
        '#title' => $this->t('Entityqueues settings'),
        '#group' => 'advanced',
        '#tree' => TRUE,
        '#weight' => 100,
        '#markup' => '<p>' . $this->t('Choose from the available entityqueues below to push this content to. To reorder and manage each queue, please visit the @entityqueue_management_page', [
          '@entityqueue_management_page' => Link::fromTextAndUrl($this->t('Entityqueue management page'), $url)->toString(),
        ]) . '</p>',
      ];
      $form['entityqueue_form_widget']['entityqueues'] = [];
      foreach ($allowed_entityqueues as $allowed_entityqueue) {
        if ($this->currentUser->hasPermission('update ' . $allowed_entityqueue['id'] . ' entityqueue') || $this->currentUser->hasPermission('manipulate all entityqueues')) {
          $form['entityqueue_form_widget']['entityqueues'][$allowed_entityqueue['id']] = $this->prepareCheckbox($entity_id, $allowed_entityqueue);
        }
      }
      // Calling submit handler.
      foreach (array_keys($form['actions']) as $action) {
        if ($action != 'preview' && isset($form['actions'][$action]['#type']) && $form['actions'][$action]['#type'] === 'submit') {
          $form['actions'][$action]['#submit'][] = [static::class, 'nodeFormSubmit'];
        }
      }
    }
  }

  /**
   * Submit callback for the node form.
   *
   * Static bridge so the callback stays serializable in the form cache; the
   * work happens in the instantiated hook service.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public static function nodeFormSubmit(array $form, FormStateInterface $form_state): void {
    \Drupal::service(static::class)->doNodeFormSubmit($form, $form_state);
  }

  /**
   * Adds or removes the node from the selected entityqueues on submit.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function doNodeFormSubmit(array $form, FormStateInterface $form_state): void {
    $node = $form_state->getFormObject()->getEntity();
    if (!$form_state->isValueEmpty('entityqueue_form_widget')) {
      $values = $form_state->getValue('entityqueue_form_widget');

      // Initialize variables with default values.
      $publish_on_timestamp_value = NULL;

      // Check if the node has the 'publish_on' field.
      if ($node->hasField('publish_on') && $node->get('publish_on')->getValue()) {
        // Get publish on timestamp.
        $publish_on_timestamp_value = $node->get('publish_on')->getValue()[0]['value'];
      }

      // Check if there's a published version of this node (any revision).
      $published_revisions = $this->entityTypeManager->getStorage('node')
        ->getQuery()
        ->condition('nid', $node->id())
        ->condition('status', 1)
        ->allRevisions()
        ->accessCheck(FALSE)
        ->execute();
      $has_published_version = !empty($published_revisions);

      $subqueue_storage = $this->entityTypeManager->getStorage('entity_subqueue');

      // When Adding entity to all checked queues.
      // If the node was unpublished, do not attempt to add it to any queue.
      $eqs_machine_names = $node->isPublished() || isset($publish_on_timestamp_value) ? array_keys($values['entityqueues'], "1") : [];
      foreach ($eqs_machine_names as $eqs_machine_name) {
        $result = $subqueue_storage->getQuery()
          ->accessCheck(TRUE)
          ->condition('name', $eqs_machine_name)
          ->execute();
        /** @var \Drupal\entityqueue\Entity\EntitySubqueue[] $subqueues */
        $subqueues = $subqueue_storage->loadMultiple($result);

        foreach ($subqueues as $subqueue) {
          if (!$subqueue->hasItem($node)) {
            $subqueue->addItem($node);
            $subqueue->save();
          }
        }
      }

      // When Removing entity from all un-checked queues.
      // Only remove from unchecked queues if a published version exists.
      // If there's no published version, remove from all queues.
      $eqs_machine_names_delete = $has_published_version ? array_keys($values['entityqueues'], "0") : array_keys($values['entityqueues']);
      foreach ($eqs_machine_names_delete as $eqs_machine_name) {
        /** @var \Drupal\entityqueue\Entity\EntitySubqueue|null $entity_subqueue */
        $entity_subqueue = $subqueue_storage->load($eqs_machine_name);

        if ($entity_subqueue && $entity_subqueue->hasItem($node)) {
          $entity_subqueue->removeItem($node);
          $entity_subqueue->save();
        }
      }
    }
  }

  /**
   * Gets the allowed subqueues for the given node.
   *
   * @param object $node
   *   A node.
   *
   * @return array
   *   List of allowed sub-queue.
   */
  public function getAllowedSubqueueList($node): array {
    $allowed_entityqueues = [];
    /** @var \Drupal\entityqueue\Entity\EntitySubqueue[] $subqueues */
    $subqueues = $this->entityTypeManager->getStorage('entity_subqueue')->loadMultiple();

    if (count($subqueues) > 0) {
      foreach ($subqueues as $subqueue) {
        $queue = $subqueue->getQueue();
        if ($queue) {
          $queue_settings = $queue->getEntitySettings();
          $target_bundles = [];

          if (!empty($queue_settings['handler_settings']['target_bundles'])) {
            $target_bundles = $queue_settings['handler_settings']['target_bundles'];
          }

          if ($queue_settings['target_type'] == $node->getEntityTypeId()
            && (empty($target_bundles) || in_array($node->bundle(), $target_bundles))) {

            $id = $subqueue->id();
            $allowed_entityqueues[$id]['id'] = $id;
            $allowed_entityqueues[$id]['act_as_queue_status'] = $queue->getActAsQueue();
          }
        }
      }
    }

    return $allowed_entityqueues;
  }

  /**
   * Prepares a checkbox for an allowed entityqueue.
   *
   * @param int $entity_id
   *   Entity ID.
   * @param array $allowed_entityqueue
   *   The allowed entityqueue info (id, act_as_queue_status).
   *
   * @return array
   *   Values needed to render a checkbox.
   */
  public function prepareCheckbox($entity_id, array $allowed_entityqueue): array {
    // Get all checked queues for this entity.
    $query_checked_queues = $this->database->select('entity_subqueue__items')
      ->distinct()
      ->condition('items_target_id', $entity_id);
    $query_checked_queues->addField('entity_subqueue__items', 'entity_id');
    $result_checked_queues = array_keys($query_checked_queues->execute()
      ->fetchAllAssoc('entity_id'));

    /** @var \Drupal\entityqueue\Entity\EntitySubqueue $entity_subqueue */
    $entity_subqueue = $this->entityTypeManager
      ->getStorage('entity_subqueue')
      ->load($allowed_entityqueue['id']);

    $number_of_items = count($entity_subqueue->get('items')->getValue());

    $queue_details = $entity_subqueue->getQueue();
    $max_size = $queue_details->getMaximumSize();
    $max_size = ($max_size == '0' ? 'unlimited' : $max_size);

    $checked_flag = 0;
    if (in_array($allowed_entityqueue['id'], $result_checked_queues)) {
      $checked_flag = 1;
    }

    $queue = $entity_subqueue->getQueue();
    if ($queue->getHandler() == 'multiple') {
      $title = $entity_subqueue->label() . ' (' . $queue->label() . ')';
    }
    else {
      $title = $entity_subqueue->label();
    }

    $checkbox_status = FALSE;
    if (($number_of_items >= $max_size)
      && $checked_flag == 0
      && !$allowed_entityqueue['act_as_queue_status']
      && ($max_size != 0 || $max_size != NULL)) {
      $checkbox_status = TRUE;
    }

    return [
      '#type' => 'checkbox',
      '#title' => $this->t('@queue_title <i>(@number_of_items out of @$max_size items)</i>', [
        '@queue_title' => $title,
        '@number_of_items' => $number_of_items,
        '@$max_size' => $max_size,
      ]),
      '#default_value' => $checked_flag,
      '#id' => $allowed_entityqueue['id'],
      '#attributes' => [
        'disabled' => $checkbox_status,
      ],
    ];
  }

}
