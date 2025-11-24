# Configuration

Learn how to configure the Entityqueue Form Widget and entity queues for your Drupal site.

## Overview

The Entityqueue Form Widget has minimal configuration needs. Most setup occurs through the Entityqueue module itself. Once entity queues are created and configured, the widget automatically displays them on appropriate node forms.

## Creating Entity Queues

### Accessing the Entity Queue Management

1. Navigate to **Administration > Structure > Entity Queues** (`/admin/structure/entityqueue`)
2. This is where you create and manage all entity queues used by the form widget

### Creating a New Entity Queue

1. Click **Add Entity Queue** on the Entity Queues page
2. Fill in the queue details:

#### Basic Settings

| Field | Description | Example |
|-------|-------------|---------|
| **Name** | Machine-readable identifier | `featured_articles` |
| **Label** | Human-readable display name | "Featured Articles" |
| **Description** | Queue purpose (optional) | "Articles featured on homepage" |

#### Handler Configuration

| Setting | Purpose |
|---------|---------|
| **Handler** | Determines queue type and behavior |
| **Target entity type** | Which entities this queue can contain (usually "Node") |
| **Target bundles** | Which content types can be added (e.g., Article, Page) |

#### Queue Settings

| Setting | Purpose | Default |
|---------|---------|---------|
| **Min size** | Minimum items allowed in queue | 0 |
| **Max size** | Maximum items allowed | Unlimited |
| **Handler settings** | Type-specific configuration | Varies |

### Step-by-Step Queue Creation Example

**Create a "Featured Articles" queue**:

1. Go to `/admin/structure/entityqueue`
2. Click "Add Entity Queue"
3. Enter:
   - Name: `featured_articles`
   - Label: "Featured Articles"
   - Description: "Articles featured on the homepage banner"
4. Select Handler: "Simple node queue" (or your preferred handler)
5. Target entity type: "Node"
6. Target bundles: Check "Article" only
7. Max size: "5" (limit to 5 featured articles)
8. Click **Save**

The queue now appears in the Entityqueue Form Widget on all Article creation/edit forms.

## Configuring Queue Visibility

### Controlling Which Content Types See Queues

#### By Content Type

1. Go to the queue you created
2. Check the "Target bundles" setting
3. Select which content types can be added to this queue
4. Only matching content types will show the queue in the form widget

#### By Entity Type

Most queues target "Node" entity type. You can also create queues for:
- Media
- Taxonomy terms
- Custom entities
- Other entity types supported by Entityqueue

### Example Configurations

**Scenario 1: Blog with Multiple Content Types**

Queue: "Featured Content"
- Target bundles: Article, News, Event (all show in widget)
- Result: Widget shows on all three content types

**Scenario 2: E-commerce Product Queues**

Queue: "Staff Picks"
- Target entity type: Node
- Target bundles: Product only
- Result: Widget shows only on Product content type

**Scenario 3: Separate Queues per Content Type**

Queue 1: "Featured Articles" → Article bundle only
Queue 2: "Featured Pages" → Page bundle only
Queue 3: "Featured Products" → Product bundle only
Result: Each content type sees only its relevant queues

## Queue Sizing and Constraints

### Setting Queue Limits

```
Min Size: 0 (optional minimum)
Max Size: 10 (maximum items)
```

### What Queue Limits Mean

- **Min Size**: Minimum items required (can be 0 for optional)
- **Max Size**: Maximum items allowed (can be unlimited)

### Examples

| Queue | Min | Max | Purpose |
|-------|-----|-----|---------|
| Featured | 0 | 5 | Homepage features |
| Related | 3 | 10 | Curated suggestions |
| Category | 0 | 50 | Topic grouping |

### Behavior When Queue Is Full

When a queue reaches its maximum size:
- Widget may show a warning
- Oldest items may be removed (depending on handler)
- Admin interface handles overflow management
- Editors can remove items to make space

## Handler Types

### Common Handlers

#### Simple Node Queue Handler

- **Purpose**: Basic ordered queue of nodes
- **Best for**: Featured content, related items, curated lists
- **Configuration**: Minimal
- **Features**:
  - Simple ordering via drag-and-drop
  - Size limits enforcement
  - Standard CRUD operations

#### Other Handlers

Handlers may vary by Entityqueue version. Common alternatives include:
- **Media queue handler** - For queuing media items
- **Custom handlers** - Site-specific implementations

Check your Entityqueue module documentation for available handlers.

## Queue Ordering

### Default Ordering

Queues maintain item order for display. Items appear in the order they were added (FIFO) or by weight if configured.

### Reordering Items

Reorder items in a queue:

1. Navigate to **Administration > Structure > Entity Queues**
2. Click on the queue name
3. Use drag-and-drop to reorder items
4. Save changes

**Note**: The form widget doesn't allow reordering. Use the queue administration interface for ordering.

## Queue Publishing

### Active vs Inactive Queues

Queues can be enabled or disabled:

- **Enabled**: Visible in the form widget
- **Disabled**: Hidden from editors, can be re-enabled later

### Disabling Queues

To temporarily hide a queue:

1. Edit the queue
2. Uncheck "Status" or "Active" field (depending on version)
3. Save

The queue will be hidden from the form widget but data remains intact.

### Archiving Old Queues

For queues no longer in use:

1. Disable the queue
2. Rename with archive prefix: "ARCHIVE: Old Queue Name"
3. Document when archived and why
4. Keep in system for historical reference

## Testing Queue Configuration

### Verify Queue Appears in Widget

1. Create/edit a node of the target content type
2. Check right sidebar for "Entityqueues" field group
3. Verify your queue appears with correct name
4. Test checking/unchecking boxes
5. Save and verify assignment persists

### Troubleshooting Configuration

| Issue | Cause | Solution |
|-------|-------|----------|
| Queue doesn't appear | Wrong target bundle | Edit queue, select correct bundles |
| Queue appears but empty | Misconfigured | Check queue handler settings |
| Can't add items | No permission | Adjust permission settings |
| Queue limit ignored | Handler issue | Verify handler configuration |

## Best Practices

### Queue Naming

✓ **Do**:
- Use clear, descriptive names: "Homepage Featured"
- Include context: "2024 Winter Campaign"
- Use prefixes for organization: "Blog: Featured", "Product: Sale"

✗ **Don't**:
- Use generic names: "Featured" (featured where?)
- Use vague names: "Important", "Special"
- Use abbreviations: "FP", "HWF"

### Queue Organization

✓ **Do**:
- Group related queues: All homepage queues together
- Document queue purposes
- Limit queue count (3-8 per content type)
- Clean up old queues

✗ **Don't**:
- Create too many queues (decision paralysis)
- Leave disabled queues in the system
- Use queues for things better handled by taxonomy/fields
- Duplicate queue purposes

### Queue Maintenance

**Monthly**:
- Review queue contents
- Remove outdated items
- Check for queue health

**Quarterly**:
- Assess queue effectiveness
- Merge similar queues if possible
- Archive unused queues

**Annually**:
- Comprehensive queue audit
- Update queue documentation
- Retire obsolete queues

## Configuration Checklist

Before launching queues, verify:

- [ ] All required queues created
- [ ] Target content types correctly set
- [ ] Queue names are clear and consistent
- [ ] Size limits configured appropriately
- [ ] Queue purposes documented
- [ ] Permissions assigned to editorial team
- [ ] Tested with different content types
- [ ] Training provided to editors
- [ ] Monitoring plan in place

## Next Steps

- [Configure permissions](1-permissions.md)
- [Manage queues effectively](2-queue-management.md)
- [Performance optimization](3-performance.md)
