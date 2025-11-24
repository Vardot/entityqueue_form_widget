# Frequently Asked Questions

Common questions about the Entityqueue Form Widget module.

## Installation & Setup

### Q: What are the minimum requirements?
**A**: Drupal 10 or 11, PHP 8.3+, and the Entityqueue module must be installed and enabled. See [Installation Guide](1-users/0-installation.md) for details.

### Q: Why doesn't the widget appear on my node form?
**A**: Common causes:
1. Entityqueue module not installed/enabled
2. No entity queues created
3. Queues don't target your content type
4. You lack permission to manage queues
5. Browser cache not cleared

See [Installation Troubleshooting](1-users/0-installation.md#troubleshooting-installation) for solutions.

### Q: Can I use this with content types other than nodes?
**A**: The module primarily targets nodes. Support for other entity types depends on Entityqueue configuration and would require custom development.

### Q: Do I need to install any dependencies besides Entityqueue?
**A**: No. Entityqueue is the only required dependency. The module is intentionally lightweight.

## Usage & Workflow

### Q: Can one content item be in multiple queues?
**A**: Yes! You can assign a single node to multiple queues by checking multiple boxes before saving.

### Q: How do I reorder items in a queue?
**A**: Queue reordering is done in the queue administration interface at `/admin/structure/entityqueue`, not in the form widget. Click the queue name to manage ordering.

### Q: Can I set a queue as "required"?
**A**: The form widget doesn't support required queues. All queue assignments are optional. You could implement custom validation using form alter hooks.

### Q: What happens if I remove content from all queues?
**A**: The content remains, but is not displayed in any queue-based sections. The content itself is unchanged.

### Q: Can I bulk-edit queue assignments?
**A**: The form widget handles one node at a time. For bulk operations, use the queue administration pages at `/admin/structure/entityqueue/[queue]`.

### Q: How do I backup my queue assignments?
**A**: Queue assignments are stored in the database. Use standard Drupal database backup procedures. See [Performance Guide](2-admins/3-performance.md#backup-strategy).

## Permissions & Access

### Q: Why can't I see the widget even though I'm an admin?
**A**: Check:
1. Entityqueue module is enabled
2. At least one queue exists
3. Queue targets your content type
4. No queue-specific permissions are blocking access
5. Clear browser cache

### Q: Can I hide the widget from certain user roles?
**A**: Yes. Use `hook_form_alter()` to check user roles and hide the widget:

```php
function mymodule_form_alter(&$form, &$form_state, $form_id) {
  $user = \Drupal::currentUser();
  if (!$user->hasRole('editor')) {
    unset($form['entityqueue_form_widget']);
  }
}
```

### Q: How do I grant a user permission to only one queue?
**A**: Each queue has separate permissions. At `/admin/people/permissions`, search for the queue name and check only the permissions for that queue for the user's role.

### Q: What's the difference between "Create", "Edit", and "Delete" permissions?
**A**:
- **Create**: Add content to queue
- **Edit**: Change existing queue assignments
- **Delete**: Remove content from queue

### Q: Can anonymous users see the widget?
**A**: No. The widget only appears on node edit/create forms, which require authentication.

## Configuration & Management

### Q: How many queues is too many?
**A**: 5-10 queues per content type is ideal. More queues can confuse editors and impact performance. Consolidate similar purposes.

### Q: Can I create queues that target multiple content types?
**A**: Yes. When creating a queue, you can select multiple target bundles (content types).

### Q: How do I archive a queue without losing data?
**A**:
1. Rename it with "ARCHIVE:" prefix
2. Disable the queue (uncheck status)
3. Keep it in the system for historical reference

See [Queue Management](2-admins/2-queue-management.md#queue-archiving) for details.

### Q: What's the maximum queue size?
**A**: Technically unlimited, but recommended max is 50-200 items depending on use. Larger queues impact performance.

### Q: Can I automatically remove old items from queues?
**A**: The base module doesn't include automatic cleanup. You can implement this via custom code, cron hooks, or contributed modules.

## Performance & Optimization

### Q: Is the widget slow with many queues?
**A**: If the widget is slow:
1. Reduce queue count (consolidate)
2. Ensure only relevant queues are visible
3. Enable Drupal caching
4. Check database performance
5. Clear all caches

See [Performance Guide](2-admins/3-performance.md) for optimization strategies.

### Q: How many database queries does the widget create?
**A**: Typically 3-10 queries depending on queue count. Each queue may require a separate query to load items.

### Q: Should I use Redis/Memcache with this module?
**A**: Not required for small sites, but recommended for sites with:
- Many queues (15+)
- High traffic
- Large queue item counts (200+)

### Q: Can I customize the widget markup without modifying the module?
**A**: Yes. Override templates in your theme or use form alter hooks. See [Theme Integration](3-developers/3-theme-integration.md).

## Troubleshooting

### Q: The widget displays but checkboxes don't save
**A**: Check:
1. User has "Edit [Queue] subqueues" permission
2. User can edit the node itself
3. No form validation errors
4. Browser console for JavaScript errors
5. Clear browser cache

### Q: Queue assignments disappear after saving
**A**: This indicates a permissions or validation issue:
1. Verify edit permission
2. Check for form errors
3. Review Drupal logs for errors
4. Test with admin account

### Q: Multiple queue widgets appear on form
**A**: This suggests:
1. Module is enabled twice (unlikely)
2. Custom form alter is duplicating widget
3. Field is being rendered multiple times

Check `/admin/config/development/logging` for error messages.

### Q: Getting "Widget not showing" error
**A**: This isn't an error message from the module. It means:
1. No queues exist for your content type
2. User lacks view/edit permissions
3. Queues target different content type
4. Widget is hidden by custom code

### Q: Queue appears empty but should have items
**A**: Check:
1. Items were actually assigned to queue
2. Items haven't been removed/deleted
3. Database isn't corrupted
4. Queue configuration is correct

## Developer Questions

### Q: Can I extend the widget for custom entity types?
**A**: Yes. You'll need to implement custom form alteration and possibly custom handlers. See [Customization Guide](3-developers/0-customization.md).

### Q: How do I programmatically add content to queues?
**A**: See [API Reference](3-developers/1-api-reference.md) for code examples on adding/removing content via the service.

### Q: What hooks are available for customization?
**A**: Available hooks:
- `hook_form_alter()` - Modify form/widget
- `hook_entityqueue_form_widget_alter()` - Custom widget alter hook
- `hook_entityqueue_widget_queue_list_alter()` - Customize queue list

See [API Reference](3-developers/1-api-reference.md) for details.

### Q: Can I integrate with custom modules?
**A**: Yes. Use the service `entityqueue_form_widget.service` or implement the hooks. See [Architecture Guide](3-developers/2-architecture.md).

### Q: How do I theme the widget?
**A**: Create custom templates in your theme's `templates/form/` directory. See [Theme Integration](3-developers/3-theme-integration.md).

## Drupal Core Compatibility

### Q: Will this work with Drupal 12?
**A**: The module should work with Drupal 12 once released, assuming Entityqueue is also compatible. The current branch is maintained for Drupal 10 and 11.

### Q: Is this compatible with Gutenberg or layout modules?
**A**: The widget appears in the standard Drupal form sidebar, separate from content editing. It should work alongside layout/block modules.

### Q: Does this work with Media module?
**A**: Media is a different entity type. This widget is for nodes. Media queue management would require Entityqueue configured for media entities.

## Support & Help

### Q: Where can I report bugs?
**A**: Visit the [issue queue on Drupal.org](https://www.drupal.org/project/issues/entityqueue_form_widget).

### Q: How can I request a feature?
**A**: Post a feature request in the [issue queue](https://www.drupal.org/project/issues/entityqueue_form_widget).

### Q: Is there a Slack/Discord community?
**A**: Check the [project page](https://www.drupal.org/project/entityqueue_form_widget) for community links and resources.

### Q: Can I hire someone to customize this?
**A**: Yes. The maintainer (Vardot) offers consulting services. Check their website or the project page for contact information.

### Q: How do I contribute improvements?
**A**: See the module's repository on GitLab. Submit merge requests with improvements, bug fixes, or features.

## More Help

- See the [main documentation index](index.md)
- Visit the [Drupal project page](https://www.drupal.org/project/entityqueue_form_widget)
- Check [Entityqueue documentation](https://www.drupal.org/project/entityqueue) for queue-specific questions
- Open an issue in the [issue queue](https://www.drupal.org/project/issues/entityqueue_form_widget)
