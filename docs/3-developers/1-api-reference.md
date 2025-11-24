# API Reference

Complete API documentation for the Entityqueue Form Widget module.

## Module Hooks

### hook_entityqueue_form_widget_alter

Allows modules to alter the Entityqueue Form Widget before rendering.

**Signature:**
```php
function hook_entityqueue_form_widget_alter(&$widget, $entity, $entity_type)
```

**Parameters:**
- `&$widget` (array): The widget form array, passed by reference
- `$entity` (EntityInterface): The entity being edited
- `$entity_type` (string): The entity type (e.g., 'node')

**Example:**
```php
/**
 * Implements hook_entityqueue_form_widget_alter().
 */
function mymodule_entityqueue_form_widget_alter(&$widget, $entity, $entity_type) {
  // Modify widget for nodes only
  if ($entity_type === 'node') {
    foreach ($widget as $queue_id => &$queue_element) {
      if (is_numeric($queue_id)) {
        $queue_element['#description'] = 'Select to include in this queue';
      }
    }
  }
}
```

### hook_entityqueue_widget_queue_list_alter

Alter the list of queues available in the widget.

**Signature:**
```php
function hook_entityqueue_widget_queue_list_alter(&$queues, $entity, $entity_type)
```

**Parameters:**
- `&$queues` (array): Array of available queues, passed by reference
- `$entity` (EntityInterface): The entity being edited
- `$entity_type` (string): The entity type

**Example:**
```php
/**
 * Implements hook_entityqueue_widget_queue_list_alter().
 */
function mymodule_entityqueue_widget_queue_list_alter(&$queues, $entity, $entity_type) {
  // Hide queues based on conditions
  foreach ($queues as $queue_id => $queue) {
    if ($queue->id() === 'private_queue' && !$entity->access('admin')) {
      unset($queues[$queue_id]);
    }
  }
}
```

## Classes and Services

### EntityqueueFormWidgetService

Main service for widget operations.

**Location:** `src/Service/EntityqueueFormWidgetService.php`

**Methods:**

#### getAvailableQueues()

Get all queues available for an entity.

```php
/**
 * Get available queues for an entity.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity.
 * @param string $entity_type
 *   The entity type.
 *
 * @return \Drupal\entityqueue\Entity\EntityQueue[]
 *   Array of available queues.
 */
public function getAvailableQueues(EntityInterface $entity, $entity_type = 'node')
```

**Usage:**
```php
$service = \Drupal::service('entityqueue_form_widget.service');
$queues = $service->getAvailableQueues($node);
```

#### getEntityQueueAssignments()

Get current queue assignments for an entity.

```php
/**
 * Get entity queue assignments.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity.
 *
 * @return array
 *   Array of queue IDs the entity is assigned to.
 */
public function getEntityQueueAssignments(EntityInterface $entity)
```

**Usage:**
```php
$assigned_queues = $service->getEntityQueueAssignments($node);
// Returns: [5, 7, 12] (queue IDs)
```

#### isEntityInQueue()

Check if entity is in a specific queue.

```php
/**
 * Check if entity is in queue.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity.
 * @param \Drupal\entityqueue\Entity\EntityQueue $queue
 *   The queue.
 *
 * @return bool
 *   TRUE if entity is in queue, FALSE otherwise.
 */
public function isEntityInQueue(EntityInterface $entity, EntityQueue $queue)
```

**Usage:**
```php
if ($service->isEntityInQueue($node, $featured_queue)) {
  echo "Node is featured!";
}
```

## Form Elements

### EntityqueueFormWidget

The form element type for the widget.

**Type:** `entityqueue_form_widget`

**Properties:**
```php
$form['entityqueues'] = [
  '#type' => 'entityqueue_form_widget',
  '#entity' => $entity,
  '#entity_type' => 'node',
  '#title' => t('Queues'),
];
```

## Database Tables

### entityqueue_queue_item

Stores individual items in queues.

**Schema:**
```
Field           | Type    | Description
----------------|---------|------------------------
id              | INT     | Primary key
queue_id        | INT     | Foreign key to queue
item_id         | INT     | Entity ID (usually node ID)
weight          | INT     | Order weight in queue
created         | BIGINT  | Creation timestamp
```

**Useful Queries:**

```sql
-- Find all items in a queue
SELECT * FROM entityqueue_queue_item
WHERE queue_id = 5
ORDER BY weight ASC;

-- Find all queues a node is in
SELECT queue_id FROM entityqueue_queue_item
WHERE item_id = 123;

-- Remove orphaned items
DELETE FROM entityqueue_queue_item
WHERE item_id NOT IN (SELECT nid FROM node);
```

## Events (if using Symfony EventDispatcher)

### EntityqueueWidgetEvent

Event dispatched when widget is altered.

**Event Name:** `entityqueue_form_widget.alter`

**Properties:**
- `widget`: The form widget array
- `entity`: The entity being edited
- `entity_type`: The entity type

**Usage:**
```php
use Drupal\Core\EventDispatcher\Event;

/**
 * Subscribe to widget alter event.
 */
public function onWidgetAlter(EntityqueueWidgetEvent $event) {
  $event->getWidget();
  $event->getEntity();
}
```

## Permissions

### Dynamic Queue Permissions

Permissions are created dynamically based on configured queues.

**Format:**
```
create [queue-id] entityqueue_subqueue
edit [queue-id] entityqueue_subqueue
delete [queue-id] entityqueue_subqueue
```

**Example:**
```php
// Check if user can edit Featured queue
if ($user->hasPermission('edit featured_articles entityqueue_subqueue')) {
  // User can edit
}
```

## Configuration Files

### Module Configuration

**Location:** `config/schema/entityqueue_form_widget.schema.yml`

**Settings:**
```yaml
entityqueue_form_widget.settings:
  type: config_object
  label: 'Entityqueue Form Widget Settings'
  mapping:
    # Add settings as available
    display_order:
      type: string
      label: 'Queue display order'
```

## Helper Functions

### entityqueue_form_widget_get_queues()

Get available queues (convenience function).

```php
/**
 * Get available queues for entity.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity.
 *
 * @return array
 *   Array of EntityQueue objects.
 */
function entityqueue_form_widget_get_queues(EntityInterface $entity)
```

**Usage:**
```php
$queues = entityqueue_form_widget_get_queues($node);
foreach ($queues as $queue) {
  echo $queue->label();
}
```

## Examples

### Example 1: Get All Queues for Node

```php
use Drupal\node\Entity\Node;

$node = Node::load(123);
$service = \Drupal::service('entityqueue_form_widget.service');
$queues = $service->getAvailableQueues($node);

foreach ($queues as $queue) {
  echo $queue->label() . ' (ID: ' . $queue->id() . ')';
}
```

### Example 2: Programmatically Add Node to Queue

```php
use Drupal\entityqueue\Entity\EntityQueue;

$queue = EntityQueue::load('featured_articles');
$node_id = 123;

$subqueue = $queue->getSubqueue(0);
$items = $subqueue->get('items')->getValue();
$items[] = ['target_id' => $node_id];
$subqueue->set('items', $items);
$subqueue->save();
```

### Example 3: Check Queue Membership

```php
$service = \Drupal::service('entityqueue_form_widget.service');
$queues = $service->getEntityQueueAssignments($node);

if (in_array('featured_articles', $queues)) {
  echo 'Node is featured!';
}
```

### Example 4: Implement Custom Hook

```php
function mymodule_form_node_article_edit_form_alter(&$form, $form_state) {
  $node = $form_state->getFormObject()->getEntity();

  // Invoke custom hook
  \Drupal::moduleHandler()->invokeAll(
    'node_queue_preprocess',
    [$node]
  );
}
```

## Next Steps

- [Customization Guide](0-customization.md)
- [Module Architecture](2-architecture.md)
- [Theme Integration](3-theme-integration.md)
