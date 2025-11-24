# Performance and Maintenance

Best practices for maintaining optimal performance of the Entityqueue Form Widget and entity queues.

## Performance Fundamentals

### What Affects Widget Performance

**Widget Load Time** (most important):
- Number of queues displayed
- Number of items in each queue
- Database queries needed
- Module caching effectiveness

**Form Rendering**:
- Browser rendering of checkboxes
- JavaScript interactions
- Form submission processing

**Backend Performance**:
- Database size
- Entityqueue module efficiency
- Drupal cache configuration

## Optimizing Widget Performance

### Limit Number of Queues per Content Type

**Problem**: Too many queues visible creates performance issues

```
Poor: 20 queues per content type
  ├─ Long load time
  ├─ Editor confusion
  ├─ Many database queries
  └─ Reduced usability

Good: 5-8 queues per content type
  ├─ Fast load time
  ├─ Clear choices
  ├─ Minimal queries
  └─ Better UX
```

**Implementation**:
1. Review all queues
2. Consolidate similar purposes
3. Archive unused queues
4. Target queues to specific bundles

### Control Queue Visibility

**By Bundle Targeting**:
1. Create queues for specific content types only
2. Don't target all bundles unnecessarily
3. Use bundle restrictions to reduce widget size

**Example**:
```
Queue 1: "Blog Featured"
  Target: Article only
  Widget shows on: Article forms only

Queue 2: "Page Featured"
  Target: Page only
  Widget shows on: Page forms only
```

**Benefit**: Each content type sees only relevant queues

### Optimize Queue Item Count

**Monitor Queue Size**:
1. Set reasonable max sizes (10-50 items typical)
2. Don't allow unlimited queue growth
3. Regularly remove old items

**Queue Size Guidelines**:

| Queue Type | Suggested Max |
|-----------|--------------|
| Homepage Featured | 5-10 |
| Category Featured | 10-20 |
| Recent Items | 20-50 |
| Large Collections | 50-200 |

### Database Optimization

#### Configure Caching

1. **Browser Cache** (most important)
   - Cache form data in browser
   - Reduces repeated requests
   - Clear on queue changes

2. **Drupal Cache**
   - Enable page caching
   - Cache form widget markup
   - Invalidate when queues change

3. **Database Cache**
   - Use Redis or Memcache if available
   - Cache queue queries
   - Reduce database load

#### Database Maintenance

**Regular Cleanup**:

```bash
# Remove old/orphaned queue entries
drush sqlq "DELETE FROM entityqueue_subqueues_items
  WHERE item_id NOT IN (SELECT nid FROM node);"

# Rebuild database indexes
drush sql:optimize
```

**Monitoring**:
- Check table sizes
- Monitor query performance
- Review slow query logs

## Monitoring and Maintenance

### Performance Monitoring

#### Key Metrics

1. **Widget Load Time**
   - Target: < 200ms to render
   - Test: Use browser DevTools
   - Monitor: Regularly on production

2. **Form Save Time**
   - Target: < 1 second
   - Test: Save node with queue assignments
   - Monitor: User feedback

3. **Queue Admin Performance**
   - Target: Queue management page loads < 500ms
   - Test: Visit `/admin/structure/entityqueue/[queue]`
   - Monitor: Admin interface responsiveness

#### Tools for Monitoring

**Built-in Drupal**:
- Drupal logs: Watch for warnings
- Performance module: Track page load times
- Watchdog: Monitor module-specific issues

**Browser Tools**:
- DevTools Network: Check request times
- DevTools Console: Look for JavaScript errors
- Performance tab: Analyze rendering

**Server Tools**:
- New Relic / DataDog: Application performance
- Database monitoring: Query analysis
- Webserver logs: Request analysis

### Regular Maintenance Tasks

**Daily**:
- Monitor error logs
- Quick performance check
- User feedback review

**Weekly**:
- Verify queue health
- Check for stale content
- Confirm all queues functioning

**Monthly**:
- Remove obsolete items
- Archive unused queues
- Update documentation
- Database optimization run

**Quarterly**:
- Performance audit
- Queue effectiveness review
- User feedback analysis
- Caching strategy review

## Caching Strategy

### Drupal Caching

#### Cache Types

**Block Cache** (Form Widget):
- Cache the form widget markup
- Invalidate when queue changes
- Significant performance boost

**Page Cache**:
- Cache entire page with widget
- Requires unique cache per user
- Good for anonymous users

**Data Cache**:
- Cache queue data/queries
- Reduce database load
- Shorter TTL for accuracy

#### Enabling Caches

1. Go to **Administration > Configuration > Performance** (`/admin/config/performance`)
2. Enable caching
3. Set cache times appropriately
4. Configure cache backend (Database/Redis)

#### Cache Invalidation

Caches automatically invalidate when:
- Queue is created/edited/deleted
- Items added/removed from queue
- Queue configuration changes

**Manual Cache Clear**:
```bash
# Clear all caches
drush cr

# Clear specific cache
drush cache:rebuild entity
```

### Browser Caching

Form widget can be aggressively cached:
- Static until queue changes
- User-agnostic (same for all editors)
- Long TTL (1-24 hours)

**Implementation**:
Use Drupal's cache headers:
```php
$form['#cache'] = [
  'max-age' => 3600, // 1 hour
  'tags' => ['entity_queue'],
];
```

## Database Performance

### Query Optimization

#### Indexes

Entityqueue module should create indexes on:
- `queue_id` (queue lookup)
- `item_id` (item lookup)
- `weight` (ordering)

**Verify Indexes**:
```bash
# List table structure
drush sqlq "DESCRIBE entityqueue_queue_item;"

# Look for indexes in output
```

#### Query Analysis

**Slow Query Log**:

1. Enable MySQL slow query log
2. Set threshold (e.g., 1 second)
3. Review logs weekly
4. Identify and optimize slow queries

**Example Optimization**:

```sql
-- Slow: Multiple joins
SELECT * FROM entityqueue_queue_item
WHERE queue_id IN (SELECT queue_id FROM entityqueue_queue WHERE target_bundle = 'article');

-- Fast: Direct query with index
SELECT * FROM entityqueue_queue_item
WHERE queue_id = 5;
```

### Database Size Management

#### Monitor Table Size

```bash
# Check Entityqueue table sizes
drush sqlq "SELECT
  table_name,
  ROUND((data_length + index_length) / 1024 / 1024, 2) as 'Size in MB'
FROM information_schema.tables
WHERE table_schema = 'drupal'
AND table_name LIKE 'entityqueue%';"
```

#### Cleanup Large Tables

```bash
# Remove orphaned subqueue items (items for deleted content)
drush sqlq "DELETE FROM entityqueue_subqueues_items
  WHERE item_id NOT IN (SELECT nid FROM node);"

# Rebuild indexes
drush sql:optimize
```

## Troubleshooting Performance Issues

### Slow Widget Load

**Diagnosis**:
- Widget takes > 1 second to appear
- Form feels sluggish
- Admin notices delays

**Investigation Steps**:

1. **Check Queue Count**
   - Count active queues for content type
   - Reduce if > 10

2. **Check Queue Item Count**
   - Review items in each queue
   - Remove unnecessary items

3. **Check Caching**
   - Verify Drupal cache enabled
   - Check cache hit rate
   - Review cache configuration

4. **Database Performance**
   - Run slow query log
   - Check table sizes
   - Verify indexes exist

**Solutions**:
- Consolidate queues
- Archive unused queues
- Reduce queue sizes
- Enable better caching
- Upgrade database hardware

### Slow Queue Admin Page

**Diagnosis**:
- Queue administration pages sluggish
- Reordering slow
- List view slow

**Solutions**:
1. Reduce items in queue
2. Improve database indexes
3. Upgrade server resources
4. Enable query caching
5. Consider alternative reordering UI

### Out of Memory Errors

**Diagnosis**:
- PHP out of memory errors
- Form doesn't load
- Save fails with error

**Causes**:
- Too many items in queue
- Too many queues displayed
- Database query memory leak

**Solutions**:
```bash
# Increase PHP memory limit in php.ini
memory_limit = 256M

# Or in wp-config.php (if applicable)
define('WP_MEMORY_LIMIT', '256M');
```

Also address root cause (reduce queue size).

## Scaling Considerations

### Small Site (1-5 queues, < 50 items each)

**Recommended Setup**:
- Default Drupal caching
- Standard database
- No special optimization needed

**Monitoring**:
- Basic logging
- Monthly reviews

### Medium Site (5-15 queues, 50-200 items each)

**Recommended Setup**:
- Enable aggressive caching
- Database indexes optimized
- Redis/Memcache recommended
- Regular maintenance schedule

**Monitoring**:
- Weekly performance checks
- Database cleanup monthly
- Query optimization as needed

### Large Site (15+ queues, 200+ items per queue)

**Recommended Setup**:
- Advanced caching (Redis)
- Database replication
- Separate read/write databases
- Queue optimization critical
- Custom handlers for performance

**Monitoring**:
- Daily performance monitoring
- Real-time alerts
- Dedicated DBA support
- Quarterly audits

## Load Testing

### Simulating Load

**Test Before Production Changes**:

1. **Form Load Testing**
   - Simulate 100s of concurrent form loads
   - Measure response time
   - Identify bottlenecks

2. **Queue Access Testing**
   - Many users viewing/editing queues
   - Monitor database load
   - Check for timeouts

**Tools**:
- Apache JMeter
- Siege
- LoadRunner
- Custom scripts

### Expected Performance Targets

```
Widget load: < 200ms
Form save: < 1 second
Queue admin: < 500ms
Database query: < 100ms
```

## Disaster Recovery

### Backup Strategy

**Backup Queue Data**:

```bash
# Export queue configuration
drush config:export --destination=/backups/entityqueue-config

# Export queue data (if module supports it)
# Or use database backup
```

**Recovery**:
```bash
# Restore configuration
drush config:import --source=/backups/entityqueue-config

# Restore database from backup
mysql drupal < backup.sql
```

### Data Integrity

**Verify Queue Integrity**:

```bash
# Check for orphaned items
drush sqlq "SELECT COUNT(*) FROM entityqueue_subqueues_items
  WHERE item_id NOT IN (SELECT nid FROM node);"

# If > 0, clean up orphaned items
```

## Next Steps

- [Queue management best practices](2-queue-management.md)
- [Configure permissions](1-permissions.md)
- [Monitor queue health](2-queue-management.md#monitoring-queue-health)
