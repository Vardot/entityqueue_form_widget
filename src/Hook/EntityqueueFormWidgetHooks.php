<?php

namespace Drupal\entityqueue_form_widget\Hook;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Hook implementations for entityqueue_form_widget.
 */
class EntityqueueFormWidgetHooks {
  use StringTranslationTrait;

  /**
   * Implements hook_form_node_form_alter().
   */
  #[Hook('form_node_form_alter')]
  public function formNodeFormAlter(&$form, FormStateInterface $form_state) {
    $node = $form_state->getFormObject()->getEntity();
    $entity_id = $node->id();
    // Works with entityqueue module version 8.x-1.0-alpha6 .
    $allowed_entityqueues = entity_qget_allowed_subque_list($node);
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
        if (\Drupal::currentUser()->hasPermission('update ' . $allowed_entityqueue['id'] . ' entityqueue') || \Drupal::currentUser()->hasPermission('manipulate all entityqueues')) {
          $form['entityqueue_form_widget']['entityqueues'][$allowed_entityqueue['id']] = _prepare_checkbox($entity_id, $allowed_entityqueue);
        }
      }
      // Calling submit handler.
      foreach (array_keys($form['actions']) as $action) {
        if ($action != 'preview' && isset($form['actions'][$action]['#type']) && $form['actions'][$action]['#type'] === 'submit') {
          $form['actions'][$action]['#submit'][] = 'entityqueue_form_widget_form_node_form_submit';
        }
      }
    }
  }

}
