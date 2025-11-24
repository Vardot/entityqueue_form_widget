# Customization Guide

Learn how to customize and extend the Entityqueue Form Widget for advanced use cases.

## Overview

The Entityqueue Form Widget is built on Drupal's form API and hooks system, making it flexible and extensible. This guide covers common customization scenarios.

## Form Alteration

### Modifying the Widget with Hook Alter

The widget can be customized using standard Drupal form alter hooks.

#### Hide Specific Queues

**Scenario**: Hide certain queues from some users or content types

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Target node forms only
  if (strpos($form_id, 'node_') !== 0 || strpos($form_id, '_edit_form') === false) {
    return;
  }

  // Hide specific queue from widget
  if (isset($form['entityqueue_form_widget']['queue_id_5'])) {
    $form['entityqueue_form_widget']['queue_id_5']['#access'] = false;
  }
}
```

#### Modify Queue Labels

**Scenario**: Change queue labels dynamically

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    if (isset($form['entityqueue_form_widget'])) {
      foreach ($form['entityqueue_form_widget'] as $queue_id => &$queue_element) {
        if (is_array($queue_element) && isset($queue_element['#title'])) {
          // Add custom context to title
          $queue_element['#title'] .= ' [Featured]';
        }
      }
    }
  }
}
```

#### Add Help Text to Queues

**Scenario**: Provide guidance to editors about queue purposes

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_article_edit_form') === 0) {
    if (isset($form['entityqueue_form_widget']['queue_featured'])) {
      $form['entityqueue_form_widget']['queue_featured']['#description'] =
        'Check this box to feature this article on the homepage. Only 5 articles can be featured at a time.';
    }
  }
}
```

### Form Submission Handling

#### Custom Processing on Save

**Scenario**: Execute custom logic when queues are assigned

```php
/**
 * Implements hook_form_submit().
 */
function mymodule_form_submit(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  $form_id = $form_state->getFormObject()->getFormId();

  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    $node = $form_state->getFormObject()->getEntity();

    // Get queue assignments
    $queues_assigned = [];
    if (isset($form['entityqueue_form_widget'])) {
      foreach ($form['entityqueue_form_widget'] as $queue_id => $queue_element) {
        if (is_numeric($queue_id) &&
            isset($form_state->getValue(['entityqueue_form_widget', $queue_id])) &&
            $form_state->getValue(['entityqueue_form_widget', $queue_id])) {
          $queues_assigned[] = $queue_id;
        }
      }
    }

    // Execute custom logic
    if (!empty($queues_assigned)) {
      // Trigger custom event, send notification, etc.
      \Drupal::moduleHandler()->invokeAll('node_featured', [$node, $queues_assigned]);
    }
  }
}
```

## Theming and Display

### Customize Widget Display

#### Alter Widget HTML

**Scenario**: Change widget HTML structure or classes

```twig
{# In your theme's templates directory #}
{# templates/form/entityqueue_form_widget.html.twig #}

<fieldset class="form-group form-group--entityqueues{{ attributes.class }}">
  {% if legend_title %}
    <legend class="fieldset__legend">
      <span class="fieldset__legend-span">{{ legend_title }}</span>
    </legend>
  {% endif %}

  <div class="fieldset__description">
    Assign this content to queues to feature it in specific sections.
  </div>

  <div class="form-section form-section--entityqueues">
    {% for key, child in children %}
      {% if key|first != '#' %}
        <div class="form-item--checkbox form-item--entityqueue">
          {{ child }}
        </div>
      {% endif %}
    {% endfor %}
  </div>
</fieldset>
```

#### Add CSS Classes

**Scenario**: Style specific queues differently

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    if (isset($form['entityqueue_form_widget'])) {
      $form['entityqueue_form_widget']['#attributes']['class'][] = 'entityqueue-widget-custom';
    }
  }
}
```

## Conditional Display

### Show/Hide Widget Based on Conditions

#### By User Role

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    $user = \Drupal::currentUser();

    // Hide widget from non-editors
    if (!$user->hasPermission('edit entityqueues')) {
      if (isset($form['entityqueue_form_widget'])) {
        $form['entityqueue_form_widget']['#access'] = false;
      }
    }
  }
}
```

#### By Content Field Value

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_article_edit_form') === 0) {
    $node = $form_state->getFormObject()->getEntity();

    // Only show featured queue for premium content
    if ($node->get('field_premium')->value) {
      // Show widget
    } else {
      if (isset($form['entityqueue_form_widget']['queue_featured'])) {
        unset($form['entityqueue_form_widget']['queue_featured']);
      }
    }
  }
}
```

## Integration with Other Modules

### Integration with Workflows

**Scenario**: Show/hide queues based on workflow state

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    $node = $form_state->getFormObject()->getEntity();

    // Only allow featured queue for published content
    if ($node->hasField('moderation_state')) {
      $state = $node->get('moderation_state')->value;

      if ($state !== 'published') {
        if (isset($form['entityqueue_form_widget']['queue_featured'])) {
          $form['entityqueue_form_widget']['queue_featured']['#disabled'] = true;
          $form['entityqueue_form_widget']['queue_featured']['#description'] =
            'Content must be published before featuring.';
        }
      }
    }
  }
}
```

### Integration with Field Permissions

**Scenario**: Respect field-level permissions on queues

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if (strpos($form_id, 'node_') === 0 && strpos($form_id, '_edit_form') !== false) {
    if (isset($form['entityqueue_form_widget'])) {
      // Check each queue's field permissions
      foreach ($form['entityqueue_form_widget'] as $queue_id => &$element) {
        if (is_numeric($queue_id)) {
          $queue = Queue::load($queue_id);

          // Check custom field permissions
          if (!current_user_can_edit_queue_field($queue)) {
            $element['#access'] = false;
          }
        }
      }
    }
  }
}
```

## JavaScript Customization

### Enhance Widget Interactions

**Scenario**: Add custom behavior to queue checkboxes

```javascript
// In your custom module JS file
(function (Drupal) {
  'use strict';

  /**
   * Custom behavior for entityqueue form widget.
   */
  Drupal.behaviors.entityqueueCustom = {
    attach: function (context, settings) {
      // Find widget checkboxes
      const checkboxes = context.querySelectorAll('.entityqueue-checkbox');

      checkboxes.forEach(checkbox => {
        // Add change event listener
        checkbox.addEventListener('change', function() {
          const queueId = this.getAttribute('data-queue-id');
          const isChecked = this.checked;

          // Custom behavior
          if (isChecked) {
            // Queue was checked
            this.parentElement.classList.add('is-queued');
            Drupal.announce('Added to queue: ' + queueId);
          } else {
            // Queue was unchecked
            this.parentElement.classList.remove('is-queued');
            Drupal.announce('Removed from queue: ' + queueId);
          }
        });
      });
    }
  };
})(Drupal);
```

### Add Visual Feedback

```javascript
// Validate queue assignments before submission
(function (Drupal) {
  Drupal.behaviors.validateEntityqueues = {
    attach: function(context, settings) {
      const form = context.querySelector('form');

      if (!form) return;

      form.addEventListener('submit', function(e) {
        const checkedQueues = form.querySelectorAll('.entityqueue-checkbox:checked');

        if (checkedQueues.length === 0) {
          const result = confirm('No queues selected. Continue anyway?');
          if (!result) {
            e.preventDefault();
          }
        }
      });
    }
  };
})(Drupal);
```

## Programmatic Queue Management

### Add Content to Queues via Code

```php
use Drupal\entityqueue\Entity\EntitySubqueue;

/**
 * Add a node to a queue programmatically.
 */
function mymodule_add_to_queue($node_id, $queue_id) {
  $queue = \Drupal\entityqueue\Entity\EntityQueue::load($queue_id);

  if (!$queue) {
    throw new \Exception("Queue $queue_id not found");
  }

  $subqueue = $queue->getSubqueue(0);

  if (!$subqueue) {
    // Create subqueue if doesn't exist
    $subqueue = EntitySubqueue::create([
      'queue' => $queue->id(),
    ]);
    $subqueue->save();
  }

  // Add item to queue
  $items = $subqueue->get('items')->getValue();
  $items[] = ['target_id' => $node_id];

  $subqueue->set('items', $items);
  $subqueue->save();
}
```

### Remove Content from Queues via Code

```php
/**
 * Remove a node from a queue programmatically.
 */
function mymodule_remove_from_queue($node_id, $queue_id) {
  $queue = \Drupal\entityqueue\Entity\EntityQueue::load($queue_id);

  if (!$queue) {
    throw new \Exception("Queue $queue_id not found");
  }

  $subqueue = $queue->getSubqueue(0);

  if (!$subqueue) {
    return;
  }

  $items = $subqueue->get('items')->getValue();
  $items = array_filter($items, function($item) use ($node_id) {
    return $item['target_id'] != $node_id;
  });

  $subqueue->set('items', $items);
  $subqueue->save();
}
```

## Advanced Hooks

### Custom Hook: Queue Assignment

Define custom hook in your module:

```php
/**
 * Implement custom queue assignment hook.
 *
 * @param \Drupal\node\Entity\Node $node
 *   The node being assigned to queues.
 * @param array $queue_ids
 *   Array of queue IDs the node is being assigned to.
 */
function hook_queue_node_assigned($node, $queue_ids) {
  // Custom implementation
}
```

Invoke in your module:

```php
\Drupal::moduleHandler()->invokeAll('queue_node_assigned', [$node, $queue_ids]);
```

## Next Steps

- [API Reference](1-api-reference.md)
- [Module Architecture](2-architecture.md)
- [Theme Integration](3-theme-integration.md)
