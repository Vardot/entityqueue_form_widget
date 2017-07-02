<?php
/**
 * @file
 * Contains Drupal\entityqueue_form_widget\Controller\EntityQueueFormWidgetController.
 */

namespace Drupal\entityqueue_form_widget\Controller;

use Drupal\Core\Controller\ControllerBase;

class EntityQueueFormWidgetController extends ControllerBase {

  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Entity Queue Form Widget Custom Page'),
    );
  }

}
