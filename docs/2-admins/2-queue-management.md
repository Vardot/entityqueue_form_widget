# Queue Management

Learn how to manage entity queues effectively on your Drupal site using the Entityqueue Form Widget.

## Queue Lifecycle

### Queue States

```
Created
  ↓
Configured
  ↓
Active (in use)
  ↓
[Maintained / Updated periodically]
  ↓
[Options: Keep Active, Archive, Delete]
```

## Creating and Managing Queues

### Queue Creation Checklist

Before creating a queue, consider:

- [ ] What is the queue's purpose?
- [ ] Which content types will use this queue?
- [ ] How many items should the queue hold?
- [ ] Who will assign items to this queue?
- [ ] How will queue be ordered?
- [ ] How long will this queue be needed?

### Organizing Queues by Purpose

**Example Organization**:

**Display/Presentation Queues**:
- "Homepage Featured"
- "Category Featured"
- "Sidebar Highlights"

**Content Grouping Queues**:
- "Recent Articles"
- "Popular Posts"
- "Staff Picks"

**Workflow Queues**:
- "Queue for Review"
- "Awaiting Publication"
- "Archived Content"

**Campaign Queues**:
- "Summer Sale 2024"
- "Holiday Campaign"
- "Product Launch"

### Queue Naming Convention

**Recommended Pattern**:
```
[Context]: [Purpose]
```

**Examples**:
- "Blog: Featured Articles"
- "Product: Staff Picks"
- "Campaign: Holiday 2024"
- "Workflow: Pending Review"

**Benefits**:
- Clear organization with colons
- Grouped in alphabetical lists
- Self-documenting purpose
- Easy to find related queues

## Queue Administration Interface

### Accessing Queue Management

1. Go to **Administration > Structure > Entity Queues** (`/admin/structure/entityqueue`)
2. See list of all configured queues
3. Click queue name to manage contents and order

### Queue List View

| Column | Shows |
|--------|-------|
| **Name** | Queue label |
| **Items** | Current items in queue |
| **Operations** | Edit/Delete actions |

### Queue Detail View

When you open a queue:

1. **Queue Information**
   - Queue name and label
   - Target entity type and bundles
   - Size limits

2. **Item List**
   - Current items with order
   - Item titles/names
   - Quick actions

3. **Ordering Interface**
   - Drag-and-drop reordering
   - Weight/position adjustment
   - Remove items directly

## Managing Queue Contents

### Adding Items to Queue (Admin Interface)

While the form widget is the primary method, admins can also add items:

1. Open the queue
2. Click "Add items"
3. Search for content
4. Select items to add
5. Save

### Removing Items from Queue

**Via Form Widget** (for editors):
- Edit the content
- Uncheck the queue
- Save

**Via Queue Admin** (for admins):
- Open the queue
- Click remove button next to item
- Confirm removal

### Reordering Items in Queue

1. Open the queue
2. Use drag-and-drop to reorder items
3. Or adjust weight values manually
4. Click Save/Update

**Order Matters**: Queue order affects display on website (typically first items shown first).

## Monitoring Queue Health

### Queue Metrics to Track

**Item Count**:
- Verify min/max sizes honored
- Ensure queue has appropriate content
- Monitor against targets

**Content Freshness**:
- Check age of oldest items
- Verify recent content included
- Identify stale items

**Assignment Patterns**:
- Track which users assign items
- Monitor assignment frequency
- Identify over/under-used queues

### Queue Health Checklist

**Monthly Review**:

- [ ] Check item count vs. limits
- [ ] Remove obsolete content
- [ ] Verify ordering is current
- [ ] Review queue effectiveness
- [ ] Check for broken references

**Quarterly Review**:

- [ ] Assess queue relevance
- [ ] Analyze usage patterns
- [ ] Consolidate similar queues if possible
- [ ] Update documentation
- [ ] Plan for seasonal changes

## Handling Queue Issues

### Queue Exceeds Maximum Size

**Issue**: Items in queue but maximum size exceeded

**Causes**:
- Content not removed when unneeded
- Queue size limit changed
- Bulk imports exceeding limit

**Solutions**:
1. Remove oldest/least relevant items
2. Increase queue max size if appropriate
3. Create separate queue for overflow
4. Implement automated cleanup (if available)

### Stale Items Not Removed

**Issue**: Old content still in queue

**Causes**:
- No removal scheduled
- Editors forget to remove
- Unclear removal criteria
- Permissions prevent removal

**Solutions**:
1. Manual removal by admin
2. Create removal checklist
3. Establish content rotation schedule
4. Document queue freshness requirements

### Duplicate Items in Queue

**Issue**: Same content appears multiple times

**Causes**:
- Content added multiple times
- Bulk operations created duplicates
- Permission/role confusion

**Solutions**:
1. Remove duplicate items
2. Review content source
3. Check bulk operation logs
4. Clarify assignment procedures

### Queue Not Showing in Widget

**Issue**: Queue created but doesn't appear on node forms

**Causes**:
- Queue disabled/archived
- Wrong target bundle
- Wrong entity type
- User lacks permissions
- Cache not cleared

**Solutions**:
1. Check queue "Status" is enabled
2. Verify target bundle is correct
3. Clear Drupal cache: `drush cr`
4. Check user permissions
5. Verify queue in list at `/admin/structure/entityqueue`

## Best Practices

### Queue Maintenance Schedule

**Daily**:
- Review newly featured content
- Monitor queue integrity

**Weekly**:
- Check featured items are still relevant
- Remove outdated content
- Verify ordering

**Monthly**:
- Comprehensive queue review
- Analyze usage metrics
- Plan adjustments

**Quarterly**:
- Strategic queue assessment
- Archive old queues
- Create new queues if needed

### Limiting Queue Proliferation

**Problem**: Too many queues leads to:
- Editor confusion
- Widget clutter
- Inconsistent usage
- Maintenance burden

**Solution**:
- Set target queue count (5-10 typical)
- Consolidate similar queues
- Archive unused queues
- Document queue purposes

### Queue Documentation

Maintain for each queue:

```
Queue Name: Featured Articles
Purpose: Display selected articles on homepage banner
Target Type: Article
Max Size: 5 items
Owner: Marketing Team
Removal Criteria: Articles older than 30 days or superseded
Typical Rotation: Weekly
Notes: Always include at least one long-form article
```

### Automating Queue Management

For advanced setups:

**Potential Automations**:
- Automatic removal of items by date
- Automatic ordering by publication date
- Automatic archiving of queues
- Automatic permission assignments

**Tools**:
- Drupal Rules/Workflows
- Custom code/hooks
- Third-party modules
- External scripts via cron

## Queue Ordering Strategies

### By Publication Date (Newest First)

**Use for**: News, blog posts, recent updates

```
Order: Descending by creation date
Result: Newest content first
```

### By Popularity/Views

**Use for**: Trending content, most read

```
Order: Descending by view count
Result: Most popular first
```

### By Manual Curation

**Use for**: Editorial picks, featured content

```
Order: Admin-defined drag-and-drop order
Result: Editor's chosen emphasis
```

### By Relevance Score

**Use for**: Recommendations, related content

```
Order: Descending by relevance algorithm
Result: Most relevant first
```

## Queue Archiving

### When to Archive Queues

Archive queues that are:
- No longer actively used
- Seasonal and out of season
- Replaced by other queues
- Kept for historical reference

### How to Archive

1. **Rename queue**: Add "ARCHIVE:" prefix
   - "ARCHIVE: 2023 Holiday Campaign"

2. **Disable queue**: Uncheck active/status

3. **Keep data**: Don't delete unless necessary

4. **Document**: Note archival date and reason

### Re-activating Archived Queues

1. Rename: Remove "ARCHIVE:" prefix
2. Enable: Check active/status
3. Review contents: Clean out stale items
4. Update permissions: Re-grant access if needed
5. Announce: Notify team queue is active again

## Queue Removal

### Before Deleting a Queue

⚠️ **Warning**: Deletion typically removes all subqueue records

Consider:

1. **Data loss**: Is historical data important?
2. **Dependencies**: Are other systems using this queue?
3. **Content**: Will removing affect site display?
4. **Audit trail**: Can queue be archived instead?

### Safe Queue Removal Process

1. **Archive first**: Rename and disable
2. **Wait period**: Keep archived for 30 days
3. **Verify**: Confirm no one needs it
4. **Backup**: Export data if needed (if supported)
5. **Delete**: Remove completely

## Performance Considerations

### Queue Size Impact

Large queues can impact:
- Form widget load time
- Database query performance
- Admin interface responsiveness

**Optimization**:
- Keep individual queue size reasonable
- Set max size limits
- Remove old items regularly
- Consider separate queues by topic

### Number of Queues Impact

Many queues increase:
- Widget clutter
- Form rendering time
- Editor decision paralysis
- Maintenance burden

**Recommendation**: Keep queue count to 5-10 per content type

## Next Steps

- [Configure queue permissions](1-permissions.md)
- [Performance optimization](3-performance.md)
- [User queue assignment workflow](../1-users/2-workflow.md)
