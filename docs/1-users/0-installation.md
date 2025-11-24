# Installation and Setup

This guide will help you install and set up the Entityqueue Form Widget module on your Drupal site.

## Requirements

### Core Requirements
- **Drupal 10** or **Drupal 11**
- **PHP 8.3** or higher
- **Entityqueue module** - [Entityqueue on Drupal.org](https://www.drupal.org/project/entityqueue)

## Installation Methods

### Method 1: Using Composer (Recommended)

```bash
composer require 'drupal/entityqueue_form_widget:^2.0'
drush en entityqueue_form_widget
drush cr
```

### Method 2: Using Drush

If the module is already downloaded:

```bash
drush en entityqueue_form_widget
drush cr
```

### Method 3: Manual Installation

1. Download the module from [Drupal.org](https://www.drupal.org/project/entityqueue_form_widget)
2. Extract the archive to your `modules/contrib` directory
3. Navigate to **Administration > Extend** (`/admin/modules`)
4. Search for "Entityqueue Form Widget"
5. Check the box next to "Entityqueue Form Widget"
6. Click "Install" at the bottom of the page
7. Clear the site cache

## Verifying Installation

After installation, verify that the module is working:

1. Ensure the **Entityqueue** module is installed and enabled
2. Create or configure at least one entity queue at **Administration > Structure > Entity Queues** (`/admin/structure/entityqueue`)
3. Navigate to create or edit a node of a type targeted by your entity queue
4. Look for the **Entityqueues** field group in the right sidebar
5. You should see available queues listed with checkboxes

## Quick Start Setup

For most sites, follow these steps:

```bash
# 1. Install required Entityqueue module first
drush en entityqueue

# 2. Install Entityqueue Form Widget
drush en entityqueue_form_widget

# 3. Clear cache
drush cr
```

## Setting Permissions

After installation, configure permissions:

1. Navigate to **Administration > People > Permissions** (`/admin/people/permissions`)
2. Search for "entityqueue" to find relevant permissions
3. Grant appropriate permissions to roles:
   - **Create [Queue Name] subqueues** - Allows adding content to queues
   - **Edit [Queue Name] subqueues** - Allows modifying queue assignments
   - **Delete [Queue Name] subqueues** - Allows removing content from queues
4. Click **Save permissions**

### Recommended Permission Assignments

**Admin Role:**
- ✓ All Entityqueue permissions

**Editor / Content Manager Role:**
- ✓ Create/Edit/Delete subqueues (for queues they should manage)

**Content Creator Role:**
- ✓ Create/Edit subqueues (limited to specific queues)

## Creating Your First Entity Queue

If you haven't created any entity queues yet:

1. Navigate to **Administration > Structure > Entity Queues** (`/admin/structure/entityqueue`)
2. Click **Add Entity Queue**
3. Enter:
   - **Name**: A machine-readable name (e.g., `featured_articles`)
   - **Label**: A human-readable name (e.g., "Featured Articles")
   - **Handler**: Select the handler for your entity type (usually "Simple node queue")
   - **Target entity type**: Select "Node" (or other entity type)
   - **Target bundles**: Select which content types can be added to this queue
   - **Min/Max size**: Set queue size limits if needed
4. Click **Save**

Once created, the queue will immediately appear in the Entityqueue Form Widget on matching node forms.

## Initial Configuration

After installation, you may want to:

1. **Create Multiple Queues**
   - Create queues for different purposes (featured, trending, related, etc.)
   - Assign different content types to different queues

2. **Set User Permissions**
   - Grant appropriate permissions to your editorial team
   - Restrict queue management to authorized users only

3. **Enable Cron**
   - Ensure your site's cron is running regularly
   - This is important for any queue-related maintenance tasks

## What Happens After Installation?

Once you enable the module:

- **Widget appears automatically** - The Entityqueue Form Widget field group appears on all node form types that have associated queues
- **Queues are visible** - All entity queues configured to target the current node type are displayed
- **No additional setup required** - The widget works immediately
- **Permissions control access** - Visibility and editability are controlled by Entityqueue permissions

## Troubleshooting Installation

### Widget Not Appearing

**Issue**: The Entityqueues field group doesn't appear on node forms

**Solutions**:
- Verify Entityqueue module is installed: `drush pm-list | grep entityqueue`
- Create at least one entity queue at `/admin/structure/entityqueue`
- Ensure the queue targets the content type you're editing
- Clear the cache: `drush cr`
- Check user permissions

### Module Won't Enable

**Issue**: Error when trying to enable the module

**Solutions**:
- Check that Entityqueue module is enabled first
- Clear the cache: `drush cr`
- Check PHP error logs for specific errors
- Verify database permissions
- Check Drupal requirements (PHP 8.3+)

### Queue Not Showing in Widget

**Issue**: Created a queue but it doesn't appear on node forms

**Solutions**:
- Verify the queue targets the correct node type
- Edit the queue and check the "Target bundles" setting
- Ensure the queue is not in a restricted/disabled state
- Clear the cache: `drush cr`
- Check permissions on the queue

## Next Steps

- [Learn how to use the widget](1-using-widget.md)
- [Understand the queue assignment workflow](2-workflow.md)
- [Configure permissions](../2-admins/1-permissions.md)
- [Create entity queues](../2-admins/2-queue-management.md)
