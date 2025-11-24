# Using the Entityqueue Form Widget

Learn how to use the Entityqueue Form Widget to assign content to entity queues directly from node creation and editing forms.

## Accessing the Widget

### Finding the Widget on Node Forms

The Entityqueue Form Widget appears automatically on node add and edit forms:

1. Navigate to **Create a new node** or **Edit an existing node**
2. Look for the **Entityqueues** field group in the **right sidebar**
3. The widget displays all available entity queues configured for that node type

If you don't see the widget:
- Ensure the Entityqueue module is enabled
- Create at least one entity queue
- Verify the queue targets your content type
- Check that you have permission to manage subqueues
- Clear your browser cache

## Understanding the Widget Interface

### Widget Components

The Entityqueue Form Widget consists of:

1. **Field Group Title**: "Entityqueues" - The section header
2. **Queue Checkboxes**: List of available queues with checkboxes
3. **Queue Names**: Clear, readable labels for each queue
4. **Status Indicators**: Visual feedback showing current queue assignments

### Queue List

The widget displays all entity queues that are:
- Configured to target your current node type
- Accessible based on your user permissions
- In an active/enabled state

## Assigning Content to Queues

### Adding a Node to a Queue

To assign a new or existing node to a queue:

1. **Create or Open a Node**
   - Navigate to create a new node, or edit an existing one
   - Ensure it's a node type that can be added to queues

2. **Locate the Entityqueues Widget**
   - Scroll to the right sidebar
   - Look for the "Entityqueues" field group

3. **Select Queues**
   - Check the boxes next to the queues where you want to add this content
   - You can select multiple queues at once

4. **Complete the Node Form**
   - Fill in all other required fields (title, body, taxonomy terms, etc.)
   - Add any other content as needed

5. **Save the Node**
   - Click the **Save** button at the bottom of the form
   - The node will be created/updated and assigned to the selected queues

6. **Verify Assignment**
   - After saving, edit the node again
   - The checkboxes should remain checked for the queues you selected
   - The content is now included in those queues

### Example Workflow

**Scenario**: You're creating a featured article

1. Click **Content > Add content > Article**
2. Enter the article title: "10 Tips for Better Content Management"
3. Write the article body
4. Scroll down to the Entityqueues field group in the right sidebar
5. Check the box for "Featured Articles" queue
6. Also check "Recent Articles" queue
7. Click **Save**
8. The article is now available in both queues

## Removing Content from Queues

### Removing a Node from a Queue

To remove a node from a queue:

1. **Edit the Node**
   - Navigate to edit the node you want to remove from a queue
   - Open the node edit form

2. **Locate the Entityqueues Widget**
   - Scroll to the right sidebar
   - Find the "Entityqueues" field group

3. **Uncheck Queues**
   - Uncheck the boxes for queues you want to remove the node from
   - Leave checked any queues where it should remain

4. **Save the Node**
   - Click **Save**
   - The node will be removed from the unchecked queues

5. **Verify Removal**
   - Edit the node again
   - Verify that the appropriate boxes are now unchecked

### Example: Removing Featured Status

**Scenario**: An article is no longer featured

1. Navigate to **Content** and find your article
2. Click **Edit**
3. In the Entityqueues field group, uncheck "Featured Articles"
4. Verify "Recent Articles" is still checked (if desired)
5. Click **Save**
6. The article is removed from the Featured queue but remains in Recent Articles

## Moving Content Between Queues

### Reassigning a Node to Different Queues

To move a node from one queue to another:

1. **Edit the Node**
   - Open the node edit form

2. **Adjust Queue Selection**
   - Uncheck the queue you want to remove it from
   - Check the queue you want to add it to
   - You can change multiple queue assignments at once

3. **Save**
   - Click **Save**
   - The node will be updated in both queues

## Managing Multiple Queue Assignments

### Adding Content to Multiple Queues

The widget allows you to assign a single node to multiple queues:

1. Open a node edit form
2. Check multiple queue checkboxes
3. Save the node
4. The node will appear in all selected queues

### Best Practices for Multiple Queues

- **Use consistent naming**: Create queues with clear, descriptive names
- **Document queue purposes**: Maintain documentation of what each queue is for
- **Limit queue overlap**: Avoid unnecessary duplicate assignments
- **Regular audits**: Periodically review which nodes are in which queues

### Example: Multi-Queue Assignment

**Scenario**: A comprehensive article relevant to multiple topics

An article about "Drupal Commerce and Content Management" might be in:
- Featured Articles
- Commerce Category
- Drupal Tips & Tricks
- Recent Articles

All selected with a single form submission.

## Widget Visibility and Permissions

### When the Widget Is Not Visible

The widget may not appear if:

1. **No Permission**: You don't have "Edit [Queue Name] subqueues" permission
2. **No Queues**: No entity queues exist for your node type
3. **Wrong Node Type**: The queues are configured for different node types
4. **Queue Disabled**: The queue is disabled or archived

### Controlling Queue Access

Ask your site administrator to grant you the appropriate permissions:
- **Create [Queue Name] subqueues** - To add content to queues
- **Edit [Queue Name] subqueues** - To modify assignments
- **Delete [Queue Name] subqueues** - To remove content from queues

## Quick Tips

### Time-Saving Tips

- **Bulk selections**: Check/uncheck multiple queues before saving to reassign to several queues at once
- **Keyboard shortcuts**: Use Tab to navigate between checkboxes
- **Common patterns**: If you frequently assign to the same queues, plan your assignments before clicking Save

### Common Actions

| Task | Steps |
|------|-------|
| Add to one queue | Check one box, save |
| Add to multiple queues | Check several boxes, save |
| Remove from all queues | Uncheck all boxes, save |
| Move between queues | Uncheck old, check new, save |
| Update queue status | Uncheck/check boxes, save |

## Troubleshooting

### Widget Shows No Queues

**Issue**: The Entityqueues field group is empty or shows no queues

**Solutions**:
1. Ask administrator to create entity queues
2. Verify you have queue assignment permissions
3. Check that queues target your content type
4. Clear browser cache

### Cannot Modify Queue Checkboxes

**Issue**: Checkboxes are disabled or grayed out

**Solutions**:
1. Contact your site administrator
2. Request the "Edit [Queue Name] subqueues" permission
3. Verify you have sufficient role permissions

### Changes Not Saving

**Issue**: Queue assignments don't persist after saving

**Solutions**:
1. Check for form validation errors (red messages)
2. Verify you have edit permissions on the node
3. Verify you have queue subqueue edit permissions
4. Try clearing your browser cache
5. Contact your site administrator

### Widget Disappeared

**Issue**: The widget that was previously showing is now gone

**Solutions**:
1. Verify the queue still exists at `/admin/structure/entityqueue`
2. Check that the queue isn't disabled
3. Verify the queue still targets your content type
4. Clear the site cache: `drush cr`

## Next Steps

- [Understand the complete workflow](2-workflow.md)
- [Explore common use cases](3-use-cases.md)
- [Learn about queue management](../2-admins/2-queue-management.md)
