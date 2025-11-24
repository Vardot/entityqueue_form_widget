# Module Architecture

Understand the internal structure and design of the Entityqueue Form Widget module.

## Overview

The Entityqueue Form Widget is a lightweight Drupal module built on standard Drupal architecture patterns. It integrates with the Entityqueue module to provide form-based queue management.

## Directory Structure

```
entityqueue_form_widget/
├── src/
│   ├── Service/
│   │   └── EntityqueueFormWidgetService.php
│   ├── Plugin/
│   │   ├── FormElement/
│   │   │   └── EntityqueueFormWidget.php
│   │   └── Field/
│   │       └── EntityqueueWidget.php
│   ├── Form/
│   │   └── SettingsForm.php
│   └── EventSubscriber/
│       └── EntityqueueFormWidgetSubscriber.php
├── templates/
│   ├── entityqueue_form_widget.html.twig
│   └── entityqueue_widget_item.html.twig
├── config/
│   ├── install/
│   │   └── entityqueue_form_widget.settings.yml
│   └── schema/
│       └── entityqueue_form_widget.schema.yml
├── entityqueue_form_widget.module
├── entityqueue_form_widget.info.yml
└── README.md
```

## Core Components

### 1. Module File (entityqueue_form_widget.module)

**Purpose**: Hook implementations and module-level functions

**Common Functions**:
```php
/**
 * Implements hook_form_alter().
 * Attaches widget to node forms.
 */
function entityqueue_form_widget_form_alter()

/**
 * Implements hook_theme().
 * Registers twig templates.
 */
function entityqueue_form_widget_theme()
```

### 2. Service (EntityqueueFormWidgetService)

**Location**: `src/Service/EntityqueueFormWidgetService.php`

**Purpose**: Core business logic for widget operations

**Responsibilities**:
- Load available queues for entity
- Check queue membership
- Manage queue assignments
- Handle permissions

**Key Methods**:
```php
public function getAvailableQueues(EntityInterface $entity, $entity_type)
public function getEntityQueueAssignments(EntityInterface $entity)
public function isEntityInQueue(EntityInterface $entity, EntityQueue $queue)
public function addToQueue(EntityInterface $entity, EntityQueue $queue)
public function removeFromQueue(EntityInterface $entity, EntityQueue $queue)
```

### 3. Form Plugin (EntityqueueFormWidget)

**Location**: `src/Plugin/FormElement/EntityqueueFormWidget.php`

**Purpose**: Defines the form element type

**Extends**: `Drupal\Core\Render\Element\FormElement`

**Key Methods**:
```php
public static function getInfo()           // Element info
public static function processWidget()     // Process element
public static function valueCallback()     // Get/set values
```

### 4. Templates (Twig)

**Location**: `templates/*.html.twig`

**Files**:
- `entityqueue_form_widget.html.twig` - Main widget wrapper
- `entityqueue_widget_item.html.twig` - Individual queue item

## Data Flow

### Widget Rendering Flow

```
hook_form_alter()
  ↓
AttachWidget to Form
  ↓
Load Available Queues
  ↓
Check Permissions
  ↓
Load Current Assignments
  ↓
Build Form Elements (Checkboxes)
  ↓
Apply Theme Template
  ↓
Render to User
```

### Form Submission Flow

```
User Submits Form
  ↓
Form Validation
  ↓
Form Processing
  ↓
Extract Widget Values
  ↓
Determine Queue Changes
  ↓
Add/Remove from Queues
  ↓
Save Entity
  ↓
Success Message
```

## Integration Points

### With Entityqueue Module

**Dependency**: `entityqueue:entityqueue`

**Integration**:
- Load EntityQueue entities
- Create/manage EntitySubqueue entities
- Respect queue permissions
- Use queue item storage

**Key Classes**:
```php
use Drupal\entityqueue\Entity\EntityQueue;
use Drupal\entityqueue\Entity\EntitySubqueue;
```

### With Drupal Form API

**Hooks Used**:
- `hook_form_alter()` - Attach widget to forms
- `hook_theme()` - Register templates
- `hook_form_BASE_FORM_ID_alter()` - Target specific forms

**Form Element**: `#type => 'entityqueue_form_widget'`

### With Permissions System

**Permission Pattern**:
```
create [queue-id] entityqueue_subqueue
edit [queue-id] entityqueue_subqueue
delete [queue-id] entityqueue_subqueue
```

**Access Checks**:
```php
$user->hasPermission('create [queue] entityqueue_subqueue')
$user->hasPermission('edit [queue] entityqueue_subqueue')
$user->hasPermission('delete [queue] entityqueue_subqueue')
```

## Module Lifecycle

### Installation

1. Module enabled via UI or Drush
2. `.module` file loaded
3. Info file processed
4. Configuration installed
5. Services registered
6. Plugin classes loaded

### Runtime

1. Form alter hooks executed
2. Widget attached to appropriate forms
3. Queues loaded and displayed
4. User interacts with checkboxes
5. Form submitted
6. Changes saved to database

### Uninstallation

1. Module disabled
2. Widget removed from forms
3. Configuration potentially retained
4. Caches cleared

## Design Patterns

### Service Injection

Services use dependency injection:

```php
class EntityqueueFormWidgetService {
  protected $entityTypeManager;
  protected $currentUser;

  public function __construct(
    EntityTypeManager $entityTypeManager,
    AccountInterface $currentUser
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
  }
}
```

### Plugin Architecture

Form element is a plugin:

```php
namespace Drupal\entityqueue_form_widget\Plugin\FormElement;

/**
 * @FormElement(
 *   id = "entityqueue_form_widget",
 *   description = @Translation("Entityqueue Form Widget"),
 * )
 */
class EntityqueueFormWidget extends FormElement {
  // Implementation
}
```

### Event-Driven Architecture

Hooks and events allow extension:

```php
// Implement hook
function hook_entityqueue_form_widget_alter(&$widget, $entity, $entity_type)

// Invoke hook
\Drupal::moduleHandler()->invokeAll('entityqueue_form_widget_alter',
  [$widget, $entity, $entity_type]);
```

## Performance Considerations

### Caching Strategy

```
Entity Queue Load
  ↓
[Cache: Entity queue config]
  ↓
Queue Item Load
  ↓
[Cache: Queue item list]
  ↓
Render Widget
  ↓
[Cache: Form widget markup]
```

### Database Queries

**Typical Query Count per Form**:
- Load queues: 1-2 queries
- Check queue membership: 1 query per queue
- Load form widget: 1 query
- **Total**: 3-10 queries typical

**Optimization**:
- Batch queue loading
- Use entity static cache
- Cache widget markup

## Security Architecture

### Permission-Based Access

```
User Action: Check Box
  ↓
Validate Permission:
  - 'create [queue] entityqueue_subqueue'
  - 'edit [queue] entityqueue_subqueue'
  - 'delete [queue] entityqueue_subqueue'
  ↓
If Permitted: Allow Action
If Denied: Block/Hide
```

### Form Validation

```
Queue Checkbox Value
  ↓
Validate Against Available Queues
  ↓
Validate User Permission
  ↓
Validate Entity Type Match
  ↓
Process if Valid
```

## Extension Points

### Hooks for Extension

```php
// Alter widget form
hook_entityqueue_form_widget_alter()

// Alter queue list
hook_entityqueue_widget_queue_list_alter()

// Custom hooks
hook_queue_node_assigned()
```

### Template Override

Override templates in theme:
```
your-theme/templates/
  └── form/
      ├── entityqueue_form_widget.html.twig
      └── entityqueue_widget_item.html.twig
```

### Service Decoration

Decorate service in `services.yml`:
```yaml
services:
  my_module.entityqueue_service:
    class: Drupal\my_module\MyEntityqueueService
    decorates: entityqueue_form_widget.service
```

## Testing Architecture

### Unit Tests

Test individual classes:
```php
class EntityqueueFormWidgetServiceTest extends UnitTestCase
```

### Functional Tests

Test form integration:
```php
class EntityqueueFormWidgetFormTest extends BrowserTestBase
```

### API Tests

Test service methods:
```php
class EntityqueueFormWidgetApiTest extends TestCase
```

## Key Dependencies

### Drupal Core Modules
- `node` - Node entity type
- `field` - Field API
- `user` - User permissions

### Contrib Modules
- `entityqueue` - Queue entity management

### External Libraries
- None (minimal dependencies)

## Version Compatibility

### Drupal Versions

**2.0.x**: Drupal 10+, 11+
- Uses modern Drupal APIs
- Service container
- Plugin system
- Symfony components

**1.x** (legacy): Drupal 8, 9
- Legacy form element approach
- Older permission system
- Different template structure

## Next Steps

- [Customization Guide](0-customization.md)
- [API Reference](1-api-reference.md)
- [Theme Integration](3-theme-integration.md)
