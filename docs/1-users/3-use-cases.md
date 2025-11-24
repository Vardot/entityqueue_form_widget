# Common Use Cases

Real-world scenarios and examples of using the Entityqueue Form Widget in your Drupal site.

## Use Case 1: Featured Content Management

### Scenario

A news website wants to feature specific articles on their homepage and category pages.

### Challenge

Editors previously had to create an article, then navigate to a separate queue management page to mark it as featured. This was inefficient and increased the chance of forgetting to feature important content.

### Solution with Entityqueue Form Widget

1. **Create queues** for different feature sections:
   - "Homepage Featured" (for main homepage highlight)
   - "Category Featured" (for category page highlights)
   - "Trending Now" (for timely content)

2. **Editor workflow**:
   - Write article
   - Check relevant featured queues
   - Save
   - Article immediately appears in selected featured sections

### Benefits

- **Time savings**: No navigation to separate management page
- **Reduced errors**: Less chance of forgetting to feature content
- **Immediate visibility**: Featured status appears immediately on save
- **Flexibility**: Easy to unfeature content by unchecking

### Example Assignments

| Article | Homepage Featured | Category Featured | Trending Now |
|---------|------------------|------------------|--------------|
| Breaking News | ✓ | ✓ | ✓ |
| In-depth Guide | ✓ | | |
| Opinion Piece | | ✓ | ✓ |
| Archived Article | | | |

## Use Case 2: E-commerce Product Curation

### Scenario

An e-commerce site wants to curate product collections such as "Staff Picks," "Seasonal," and "Sale Items."

### Implementation

1. **Create product-related queues**:
   - "Staff Recommendations"
   - "Summer Collection"
   - "Current Sales"
   - "Best Sellers"

2. **Product editor workflow**:
   - Add new product
   - Check appropriate queues:
     - Staff Pick? → Check "Staff Recommendations"
     - On sale? → Check "Current Sales"
     - Seasonal? → Check "Summer Collection"
   - Save

3. **Frontend displays**:
   - Staff Picks section shows items from "Staff Recommendations" queue
   - Seasonal carousel shows "Summer Collection"
   - Sale banner shows "Current Sales"

### Benefits

- **Cross-selling**: Products can appear in multiple curated collections
- **Seasonal management**: Easy to swap seasonal collections
- **Promotional flexibility**: Quick updates for sales or promotions
- **Editorial control**: Non-technical staff can manage collections

## Use Case 3: Blog Category Curation

### Scenario

A blog with multiple topic categories (Development, Design, Business) wants to highlight and organize posts by category and importance.

### Implementation

1. **Create category and priority queues**:
   - "Development - Featured"
   - "Design - Featured"
   - "Business - Featured"
   - "Recent Posts"
   - "Most Viewed"

2. **Author/Editor assigns**:
   - Topic-specific featured queues (1-2 per post)
   - "Recent Posts" queue for all new content
   - "Most Viewed" if tracking indicates popularity

3. **Results**:
   - Each category has featured section
   - Recent posts sidebar shows latest content
   - Popular content highlighted separately

### Benefits

- **Topic organization**: Readers find content by category
- **Highlight important**: Feature key articles per topic
- **Recency**: Keep "Recent Posts" fresh and current
- **Popularity**: Surface high-engagement content

## Use Case 4: Onboarding Content Series

### Scenario

A SaaS company wants to guide new users through onboarding content in a structured way.

### Implementation

1. **Create onboarding queues**:
   - "Onboarding: Getting Started"
   - "Onboarding: Basic Features"
   - "Onboarding: Advanced Features"
   - "Onboarding: Troubleshooting"

2. **Documentation editors assign** articles to progression queues:
   - "Create your first account" → "Getting Started"
   - "Understanding dashboards" → "Basic Features"
   - "Custom integrations" → "Advanced Features"
   - "Connection errors" → "Troubleshooting"

3. **User experience**:
   - New users see "Getting Started" content
   - As they progress, next level content becomes relevant
   - Troubleshooting always available

### Benefits

- **Guided learning**: Structured progression for new users
- **Progressive disclosure**: Content appears at right time
- **Support efficiency**: Users find answers before contacting support
- **Reduced churn**: Better onboarding = higher retention

## Use Case 5: Event-Based Content Promotion

### Scenario

A tech conference website wants to manage speaker bios, session descriptions, and sponsorship content.

### Implementation

Before Event:
1. **Create event queues**:
   - "2024 Conference: Featured Speakers"
   - "2024 Conference: Training Sessions"
   - "2024 Conference: Sponsors"
   - "Upcoming Events"

2. **Marketing assigns**:
   - Key speaker bios → "Featured Speakers"
   - Training sessions → "Training Sessions"
   - Sponsor pages → "Sponsors"
   - Event overview → "Upcoming Events"

After Event:
- Archive current queues
- Create "Past Events" queue
- Move content to historical archive

### Benefits

- **Time-limited promotion**: Easy to create and remove event-specific content groupings
- **Cross-promotion**: Features multiple related content types
- **Historical record**: Preserved for future reference
- **Quick updates**: Rapid changes during event planning

## Use Case 6: Content Versioning and Variants

### Scenario

A documentation site maintains multiple versions of guides and wants to highlight version-specific content.

### Implementation

1. **Create version-specific queues**:
   - "Documentation: Version 2.x"
   - "Documentation: Version 3.x"
   - "Latest Documentation"

2. **Technical writers assign** to version queues:
   - Guides for v2.x → "Documentation: Version 2.x"
   - Updated guides for v3.x → "Documentation: Version 3.x"
   - Current version → "Latest Documentation"

3. **Users see**:
   - Content filtered by their software version
   - Related articles for their version
   - Migration guides when available

### Benefits

- **Version clarity**: Users see content for their version
- **Deprecation management**: Easy to retire old versions
- **Cross-version links**: Guide users through version upgrades
- **Maintenance**: Organize content by version lifecycle

## Use Case 7: Editorial Calendar Management

### Scenario

A publishing company uses queues as part of their editorial calendar system.

### Implementation

1. **Create time-based queues**:
   - "Editorial: Week of Jan 1"
   - "Editorial: Week of Jan 8"
   - "Editorial: Week of Jan 15"
   - "Editorial: Queue for Review"
   - "Editorial: Published"

2. **Planning process**:
   - Content managers assign articles to weekly queues
   - "Queue for Review" holds pending content
   - Editors assign to weekly queues after review
   - "Published" tracks completed articles

3. **Publishing workflow**:
   - Write article
   - Assign to review queue
   - Editor reviews and approves
   - Move to appropriate week queue
   - Content publishes per schedule

### Benefits

- **Planning clarity**: Editorial calendar is visible
- **Workflow tracking**: See content status at a glance
- **Schedule adherence**: Publish on planned dates
- **Team coordination**: Everyone sees schedule

## Use Case 8: Recommendation Engine Foundation

### Scenario

A website wants to build personalized recommendations based on user behavior.

### Implementation

1. **Create behavioral queues**:
   - "Tech-Heavy Content"
   - "Beginner-Friendly Content"
   - "Video Tutorials"
   - "Case Studies"
   - "Quick Tips"

2. **Content team assigns** to behavioral categories:
   - Technical deep-dive → "Tech-Heavy"
   - Step-by-step guide → "Beginner-Friendly"
   - Screen recording → "Video Tutorials"

3. **Recommendation system uses** queue memberships:
   - User interested in tutorials? → Show "Video Tutorials" content
   - User prefers quick reads? → Show "Quick Tips"
   - User wants case studies? → Show "Case Studies"

### Benefits

- **Personalization**: Recommendations match user preferences
- **Machine learning ready**: Queue data enables better algorithms
- **Editorial control**: Humans decide which content goes where
- **Data-driven**: Recommendations based on quality curation

## Best Practices Across Use Cases

### Universal Recommendations

1. **Clear Queue Naming**
   - Use descriptive names: "Featured" vs "Homepage Top Banner"
   - Include context: "2024 Q1" vs generic "Quarterly"
   - Avoid ambiguity: "Important" is unclear

2. **Document Queue Purposes**
   - Maintain a queue reference guide
   - Share with entire editorial team
   - Update as queues evolve

3. **Regular Audits**
   - Monthly: Review queue contents
   - Quarterly: Assess queue relevance
   - Annually: Retire unused queues

4. **Limit Queue Count**
   - Avoid too many queues (reduces widget clarity)
   - Start with 3-5, add as needed
   - Consolidate similar purposes

5. **Consistent Assignment Logic**
   - Define assignment rules upfront
   - Train all editors on guidelines
   - Review assignments periodically

## Next Steps

- [Learn the complete workflow](2-workflow.md)
- [Configure queue permissions](../2-admins/1-permissions.md)
- [Manage queues effectively](../2-admins/2-queue-management.md)
