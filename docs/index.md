# Entityqueue Form Widget Documentation

A Drupal module that streamlines entity queue management by integrating queue assignment directly into node add and edit forms.

## What is Entityqueue Form Widget?

Entityqueue Form Widget enhances your content management workflow by allowing editors to assign content to entity queues directly from the node creation and editing forms. Instead of creating content separately from queue management, editors can now assign queues immediately while working on the content. This saves time and improves editorial efficiency.

### Key Features

- **Integrated Queue Management**: Manage entity queues directly from the node form sidebar
- **Streamlined Workflow**: No need to navigate away from content editing to manage queues
- **Multi-Queue Support**: Easily assign content to multiple queues simultaneously
- **Form-Based Assignment**: Queue assignment works alongside other node form fields
- **Automatic Widget Display**: Queues automatically appear in the form widget based on configuration
- **Compatible with Entityqueue**: Works seamlessly with the Entityqueue module

## Getting Started

### For Site Builders and Content Editors

If you're responsible for managing content and assigning queues:

- [Installation and Setup](1-users/0-installation.md) - Get started with Entityqueue Form Widget
- [Using the Widget](1-users/1-using-widget.md) - Learn how to assign content to queues
- [Queue Assignment Workflow](1-users/2-workflow.md) - Understand the complete workflow
- [Common Use Cases](1-users/3-use-cases.md) - Real-world scenarios and examples

### For Site Administrators

If you're responsible for configuring and maintaining the module:

- [Configuration](2-admins/0-configuration.md) - Configure entity queues and module settings
- [Permissions](2-admins/1-permissions.md) - Manage who can assign queues
- [Queue Management](2-admins/2-queue-management.md) - Understanding queue structure and settings
- [Performance and Maintenance](2-admins/3-performance.md) - Best practices for maintaining queues

### For Developers

If you're extending or integrating with Entityqueue Form Widget:

- [Customization Guide](3-developers/0-customization.md) - Extending the module
- [API Reference](3-developers/1-api-reference.md) - Complete API documentation
- [Module Architecture](3-developers/2-architecture.md) - Understanding the module structure
- [Theme Integration](3-developers/3-theme-integration.md) - Theming the widget

## Key Concepts

### Entity Queues

Entity queues are ordered lists of entities (typically nodes) managed by the Entityqueue module. Queues are used to:

- Create curated content collections
- Build featured content sections
- Manage related content
- Create priority-based content lists

### The Form Widget

The Entityqueue Form Widget adds a convenient sidebar field group to node forms that displays available queues and allows editors to assign or remove the current node from queues with simple checkboxes.

### Subqueues

Each queue is composed of subqueues. A subqueue is a record linking an entity (like a node) to a specific queue.

## Quick Links

- [Project Page](https://www.drupal.org/project/entityqueue_form_widget) - Drupal.org project page
- [Issue Queue](https://www.drupal.org/project/issues/entityqueue_form_widget) - Report bugs and request features
- [Entityqueue Module](https://www.drupal.org/project/entityqueue) - The required parent module

## Module Status

- **Current Version**: 2.0.7 (stable, released June 2024)
- **Drupal Compatibility**: Drupal 10 and 11
- **Status**: Covered by Drupal Security Advisory Policy
- **Maintainer**: [Vardot](https://vardot.com)

## Need Help?

- Check the [FAQ](faq.md) for common questions
- Review the relevant documentation section based on your role
- Visit the [issue queue](https://www.drupal.org/project/issues/entityqueue_form_widget) for support
