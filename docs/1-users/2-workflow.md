# Queue Assignment Workflow

Understand the complete process of working with entity queues from the perspective of a content editor using the Entityqueue Form Widget.

## The Basic Workflow

### Step-by-Step Process

#### 1. Create Content

```
Create a new article or page
  ↓
Fill in standard content fields (title, body, etc.)
  ↓
Proceed to queue assignment
```

#### 2. Assign to Queues

```
Open Entityqueues widget (right sidebar)
  ↓
Select desired queues (check boxes)
  ↓
Click Save button
```

#### 3. Confirmation

```
Content saved with queue assignments
  ↓
Widget displays updated queue status
  ↓
Content appears in selected queues
```

## Content Creation Workflow

### Complete New Article with Queue Assignment

**Scenario**: Creating a featured article

1. **Navigate to Content Creation**
   - Click **Content** > **Add content** > **Article**

2. **Fill in Article Details**
   - Title: "How to Manage Content Effectively"
   - Body: Write your article content
   - Tags: Add relevant taxonomy terms
   - Featured Image: Upload an image (if applicable)

3. **Assign to Queues**
   - Scroll down to the Entityqueues widget
   - Check "Featured Articles" queue
   - Check "Recent Articles" queue
   - Optionally check "Homepage Spotlight"

4. **Review and Save**
   - Review all content
   - Click **Save**
   - System saves the node and queue assignments

5. **Verify Assignment**
   - You see confirmation message
   - Widget now shows checked queues
   - Article appears in those queues on the website frontend

## Content Editing Workflow

### Updating Queue Assignments

**Scenario**: Article needs to be featured then unfeatured

**Week 1: Add to Featured**
1. Create article
2. Check "Featured Articles" queue
3. Save

**Week 3: Remove from Featured**
1. Edit the same article
2. Uncheck "Featured Articles"
3. Check "Archive" queue instead
4. Save

The article moves from featured to archive queue in one click.

## Multi-Queue Management Workflow

### Managing Content Across Multiple Queues

**Scenario**: Article relevant to multiple topics

```
Create article "Docker for Drupal Developers"
  ↓
Assign to multiple queues:
  - "Drupal Tips"
  - "DevOps Guides"
  - "Featured Articles"
  - "Recent Articles"
  ↓
Save once
  ↓
Article appears in all four queues
```

### Benefits of Multi-Queue Assignment

- **Single Save**: Update all queue assignments at once
- **Flexibility**: Easy to adjust assignments without affecting content itself
- **User Experience**: Readers find related content through multiple paths
- **Organization**: Content organized by multiple criteria simultaneously

## Batch Queue Updates

While the form widget is designed for individual content assignments, you can efficiently manage multiple items:

### Updating Multiple Articles

**Scenario**: Several articles should be featured

1. Edit Article 1
   - Check "Featured Articles"
   - Save

2. Edit Article 2
   - Check "Featured Articles"
   - Save

3. Edit Article 3
   - Check "Featured Articles"
   - Save

Result: Three articles now in Featured queue

**Tip**: For larger batch operations, use the queue administration interface at `/admin/structure/entityqueue` to manage items directly.

## Common Workflows by Role

### Content Creator

**Daily Workflow**:
1. Create new article
2. Add to appropriate queues
3. Publish
4. Monitor placement

**Weekly Review**:
1. Edit published articles
2. Update queue assignments based on relevance
3. Remove outdated content from queues

### Content Editor / Manager

**Review Workflow**:
1. Review pending content
2. Edit articles to improve quality
3. Assign to queues for publication
4. Monitor queue health

**Curation Workflow**:
1. Review all content in queues
2. Identify aging content
3. Edit articles to update queue assignments
4. Ensure queue relevance and freshness

### Site Administrator

**Maintenance Workflow**:
1. Create and configure entity queues
2. Set up queue permissions
3. Monitor queue performance
4. Address queue-related issues

## Workflow State Transitions

### Queue Assignment States

```
NEW CONTENT
    ↓
[Unqueued]
    ↓
EDITOR: Assigns to queues
    ↓
[In Queue]
    ↓
[Options: Stay in Queue, Move to Different Queue, Remove from Queue]
    ↓
Either: Remains Active or Archived
```

### Lifecycle of Content in Queues

1. **Created**: Article created but not in any queues
2. **Assigned**: Editor assigns to one or more queues
3. **Featured**: Content appears in selected queues on website
4. **Updated**: Content or queue assignments modified
5. **Removed**: Content taken out of queues (archived or deleted)

## Time-Based Workflow Patterns

### Daily Content Strategy

```
Morning: Create new articles
  ↓
Midday: Review and assign to queues
  ↓
Afternoon: Monitor queue appearance
  ↓
End of Day: Update assignments based on feedback
```

### Weekly Curation

```
Monday: Review all queued content
  ↓
Tuesday: Remove expired/irrelevant content
  ↓
Wednesday: Add new feature content
  ↓
Thursday: Audit queue organization
  ↓
Friday: Finalize weekly queue state
```

### Monthly Planning

```
Week 1: Plan quarterly content strategy
  ↓
Week 2: Create priority content
  ↓
Week 3: Assign to queues strategically
  ↓
Week 4: Review metrics and adjust queues
```

## Workflow Optimization

### Best Practices

1. **Consistent Naming**: Use clear queue names everyone understands
2. **Clear Guidelines**: Document when content should be in which queues
3. **Regular Review**: Periodically audit queue contents
4. **Timely Updates**: Update assignments when content status changes
5. **Communication**: Keep team informed of queue strategies

### Efficiency Tips

- **Batch during planning**: Assign multiple items to same queues if created together
- **Template approaches**: If you frequently make same assignments, follow a template
- **Keyboard navigation**: Use Tab key to quickly navigate checkboxes
- **Clear instructions**: Share queue purposes with editorial team

## Workflow Collaboration

### Team Coordination

**Content + Curation Teams**:
- Content creators: Focus on creating and initial queue assignment
- Curators: Review and optimize queue assignments
- Admins: Maintain queue infrastructure

**Communication Pattern**:
1. Creator assigns initial queues
2. Curator reviews and may reassign
3. Admin ensures queue health
4. Feedback loop improves process

### Queue Assignment Checklist

Before saving queue assignments, consider:

- [ ] Is content finished and review-ready?
- [ ] Does content fit queue criteria?
- [ ] Is content appropriate for target audience?
- [ ] Are multiple queue assignments necessary?
- [ ] Will assignment create duplicate content?
- [ ] Is content SEO and accessibility compliant?

## Handling Workflow Issues

### Common Workflow Problems

**Problem**: Content assigned to wrong queue
- Solution: Edit content immediately, uncheck wrong queue, check correct queue, save

**Problem**: Forgot to assign content to queues
- Solution: Edit content, add queues, save (retroactively assign)

**Problem**: Content needs to be in many queues
- Solution: Open form, check all applicable queues, save once (eliminates multiple edits)

**Problem**: Content no longer relevant for queue
- Solution: Edit and uncheck that queue, save

## Next Steps

- [Explore common use cases](3-use-cases.md)
- [Learn about queue management](../2-admins/2-queue-management.md)
- [Configure permissions for your team](../2-admins/1-permissions.md)
