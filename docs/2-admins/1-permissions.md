# Permissions

Learn how to manage permissions for the Entityqueue Form Widget and entity queues on your Drupal site.

## Understanding Entityqueue Permissions

The Entityqueue Form Widget respects the permission system defined by the Entityqueue module. Understanding and properly configuring these permissions is crucial for site security and workflow efficiency.

## Available Permissions

Entityqueue creates dynamic permissions based on configured queues. Each queue generates three permissions:

### Per-Queue Permissions

For each entity queue created, the following permissions are available:

1. **Create [Queue Name] subqueues**
   - Allows user to add content to the queue
   - Required for editors to assign content in the form widget
   - Example: "Create Featured Articles subqueues"

2. **Edit [Queue Name] subqueues**
   - Allows user to modify existing queue assignments
   - Required to change queue membership after content creation
   - Example: "Edit Featured Articles subqueues"

3. **Delete [Queue Name] subqueues**
   - Allows user to remove content from the queue
   - Required to uncheck items in the form widget
   - Example: "Delete Featured Articles subqueues"

### Global Entityqueue Permissions

Some Entityqueue versions may also provide:

- **Administer entity queues** - Full queue management access (admins only)
- **Access entity queue administration** - View queue administration pages

## Accessing Permission Settings

### Navigate to Permissions

1. Go to **Administration > People > Permissions** (`/admin/people/permissions`)
2. Search for "entityqueue" or specific queue names
3. Locate the queue permissions section

### Permission Grid

The permissions page displays:
- **Permission name** (left column)
- **Role checkboxes** (columns for each role)

### Setting Permissions

1. Find the permission row for your queue
2. Check the box under the desired role
3. Uncheck to remove permission
4. Scroll to bottom and click **Save permissions**

## Role-Based Permission Recommendations

### Admin Role

**Full access to all queue operations**

```
✓ Create [all queues] subqueues
✓ Edit [all queues] subqueues
✓ Delete [all queues] subqueues
✓ Administer entity queues
```

**Rationale**: Administrators need complete control for troubleshooting and configuration.

### Editor / Content Manager Role

**Selective access to curated queues**

```
✓ Create [Feature Queue] subqueues
✓ Edit [Feature Queue] subqueues
✓ Delete [Feature Queue] subqueues
✓ Create [Category] subqueues
✓ Edit [Category] subqueues
✓ Delete [Category] subqueues
✗ Administer entity queues
```

**Rationale**: Editors manage queue assignments but don't create/configure queues.

### Content Creator Role

**Limited access to specific queues**

```
✓ Create [Homepage Featured] subqueues
✓ Edit [Homepage Featured] subqueues
✗ Delete [Homepage Featured] subqueues (optional)
✓ Create [Recent Posts] subqueues
✓ Edit [Recent Posts] subqueues
✗ Delete [Recent Posts] subqueues
✗ Administer entity queues
```

**Rationale**: Creators add content to queues but can't remove or administer.

### Content Reviewer Role

**Read-only access for review purposes**

```
✗ Create [all queues] subqueues
✗ Edit [all queues] subqueues
✗ Delete [all queues] subqueues
✗ Administer entity queues
```

**Rationale**: Reviewers see queues but don't modify them (use Views/custom access).

### Anonymous / Authenticated User

**No queue permissions**

```
✗ All entityqueue permissions
```

**Rationale**: End users don't interact with queue management.

## Setting Up User Roles with Queue Permissions

### Step-by-Step Setup Example

**Scenario**: Set up roles for a news website

#### Step 1: Identify Needed Roles

1. Admin - Site administrators (full access)
2. Editor - News editors (manage featured content)
3. Writer - Content writers (create content, limited queue access)
4. Moderator - Content review (read-only queue view)

#### Step 2: Create Roles (if needed)

1. Go to **Administration > People > Roles** (`/admin/people/roles`)
2. Create role "News Editor"
3. Create role "News Writer"
4. Create role "Content Moderator"

#### Step 3: Configure Queue Permissions

**For "News Editor" role**:

1. Go to **Administration > People > Permissions**
2. Find "Featured News subqueues" section
3. Check:
   - [ ] Create Featured News subqueues
   - [ ] Edit Featured News subqueues
   - [ ] Delete Featured News subqueues
4. Find "Trending Stories subqueues" section
5. Check same three permissions
6. Save permissions

**For "News Writer" role**:

1. Check only:
   - [ ] Create Featured News subqueues
   - [ ] Create Trending Stories subqueues
2. Leave Edit/Delete unchecked
3. Save permissions

**For "Content Moderator" role**:

1. Leave all boxes unchecked (read-only)
2. Grant other permissions if using Views for queue review
3. Save permissions

#### Step 4: Assign Roles to Users

1. Go to **Administration > People** (`/admin/people`)
2. Click user to edit
3. Scroll to "Roles"
4. Check appropriate roles
5. Save user

## Permission Scenarios

### Multi-Team Environment

**Scenario**: Multiple editorial teams with different responsibilities

```
Team 1 (Tech Blog):
  - Can use: "Tech Posts Featured"
  - Cannot use: "Lifestyle Featured"

Team 2 (Lifestyle):
  - Can use: "Lifestyle Featured"
  - Cannot use: "Tech Posts Featured"
```

**Configuration**:

1. Create roles: "Tech Team", "Lifestyle Team"
2. Grant each team permissions for their queues only
3. Assign team members to appropriate roles

### Progressive Permission Levels

**Scenario**: Staff progression from writer to manager

```
Junior Writer:
  ✓ Create [all] subqueues
  ✗ Edit/Delete

Senior Writer:
  ✓ Create [all] subqueues
  ✓ Edit [all] subqueues
  ✗ Delete

Manager:
  ✓ Create/Edit/Delete [all] subqueues
  ✓ Administer queues
```

**Configuration**:

1. Create three roles with increasing permissions
2. Move users to higher roles as they progress
3. Maintains clear responsibility hierarchy

### Client vs Internal Staff

**Scenario**: Client can only manage their own queue

```
External Client:
  ✓ Create [Client Content] subqueues
  ✓ Edit [Client Content] subqueues
  ✗ Delete [Client Content] subqueues
  ✗ All other queues

Internal Staff:
  ✓ All permissions
```

**Configuration**:

1. Create client-specific queue: "Client X Content"
2. Create client role with limited permissions
3. Assign client user to role
4. Only their queue appears in widget with full permissions

## Permission Inheritance and Site Roles

### Default Drupal Roles

Entityqueue permissions interact with default roles:

#### Anonymous User
- No queue access by default
- Cannot edit content forms
- Cannot assign queues

#### Authenticated User
- May have limited queue access
- Depends on additional roles
- Should be minimal (use other roles)

#### Administrator
- Full queue access
- Can manage all permissions
- Can create/delete queues

### Custom Roles Best Practices

✓ **Do**:
- Create specific roles for queue management
- Use descriptive role names: "Content Editor", "Queue Manager"
- Grant minimal necessary permissions
- Document role purposes

✗ **Don't**:
- Grant excessive permissions
- Use overly generic roles
- Leave roles unused
- Mix responsibilities in roles

## Troubleshooting Permission Issues

### Issue: User Can't See Queues in Widget

**Diagnosis**:
- Check user's roles: **People > Edit user > Roles**
- Check role permissions: **People > Permissions**
- Search for the queue name

**Solutions**:
1. Assign user to appropriate role
2. Grant "Create [Queue] subqueues" permission
3. Verify queue targets user's content type
4. Clear cache: `drush cr`

### Issue: User Can't Save Queue Assignments

**Diagnosis**:
- User sees widget but can't check/uncheck
- Checkboxes may be disabled or non-responsive

**Solutions**:
1. Verify "Edit [Queue] subqueues" permission
2. Check "Create [Queue] subqueues" permission
3. Ensure node edit permission (separate from queue permission)
4. Check browser console for JavaScript errors

### Issue: User Can't Remove Items from Queue

**Diagnosis**:
- Can check boxes but unchecking doesn't work
- Items stuck in queue

**Solutions**:
1. Grant "Delete [Queue] subqueues" permission
2. Verify user can edit the node
3. Clear browser cache
4. Try different browser

### Issue: Widget Shows Too Many/Too Few Queues

**Diagnosis**:
- Widget shows queues user shouldn't see
- Widget shows no queues despite permissions

**Solutions**:
1. Check target bundle configuration
2. Verify queue is enabled (not archived)
3. Clear Drupal cache: `drush cr`
4. Check permission list for misspelled queue names

## Advanced Permission Scenarios

### Time-Based Permissions

Some sites use external systems to grant/revoke queue permissions:

**Scenario**: Contractor access for limited period

1. Create "Contractor" role with limited permissions
2. Set cron job to remove role after 30 days
3. Contractor sees queues during work period
4. Access automatically revoked after contract end

### Conditional Permissions via Modules

Advanced sites might use:

- **Field Permissions**: Control queue access by content field
- **Node Access**: Control queues by content ownership
- **Custom Permissions**: Implement custom queue permission logic

## Security Considerations

### Permission Scope

Entityqueue Form Widget permissions control:

✓ Can assign to queue
✓ Can edit assignment
✓ Can see queue in widget
✓ Can remove from queue

They do NOT control:

✗ Ability to edit node itself
✗ Access to queue administration
✗ View of other node's queue assignments
✗ Queue configuration

### Separation of Concerns

Recommend separating:

- **Content editing permissions** - Who can edit node content
- **Queue permissions** - Who can assign to queues
- **Queue admin permissions** - Who can configure queues

Users with content edit permission still need queue permissions to use widget.

## Permission Auditing

### Regular Permission Reviews

**Monthly**:
- Review new users' roles
- Check for unintended permission grants
- Monitor admin role changes

**Quarterly**:
- Audit all role permissions
- Remove unused roles
- Document permission changes

**Annually**:
- Complete permission audit
- Review against business requirements
- Update documentation

## Next Steps

- [Configure entity queues](0-configuration.md)
- [Manage queues effectively](2-queue-management.md)
- [Performance optimization](3-performance.md)
