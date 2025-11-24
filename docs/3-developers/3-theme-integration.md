# Theme Integration

Learn how to integrate and customize the Entityqueue Form Widget in your Drupal theme.

## Template Files

### Main Widget Template

**Default Location**: `modules/contrib/entityqueue_form_widget/templates/entityqueue-form-widget.html.twig`

**Override In Theme**: `your-theme/templates/form/entityqueue-form-widget.html.twig`

### Default Template Structure

```twig
<fieldset{{ attributes }}>
  {% if legend_title %}
    <legend>{{ legend_title }}</legend>
  {% endif %}

  <div class="form-section form-section--entityqueues">
    {% for key, child in children %}
      {% if key|first != '#' %}
        <div class="form-item form-item--entityqueue">
          {{ child }}
        </div>
      {% endif %}
    {% endfor %}
  </div>
</fieldset>
```

## Template Variables

### Available Variables in Widget Template

```twig
attributes              {# HTML attributes for fieldset #}
legend_title           {# "Entityqueues" or custom title #}
children               {# Checkbox elements #}
```

### Variables in Each Queue Item

```twig
id                     {# Checkbox ID #}
name                   {# Form element name #}
label                  {# Queue label/name #}
description            {# Queue description #}
checked                {# Boolean: is queue checked #}
disabled               {# Boolean: is queue disabled #}
required               {# Boolean: is queue required #}
attributes             {# HTML attributes #}
```

## Template Customization Examples

### Example 1: Custom Widget Container

**File**: `your-theme/templates/form/entityqueue-form-widget.html.twig`

```twig
<div class="entityqueue-widget-container">
  <div class="entityqueue-widget-header">
    <h3 class="entityqueue-widget-title">
      {% if legend_title %}
        {{ legend_title }}
      {% else %}
        {{ 'Queues'|t }}
      {% endif %}
    </h3>
    <p class="entityqueue-widget-description">
      {{ 'Select queues to include this content in different sections.'|t }}
    </p>
  </div>

  <div class="entityqueue-widget-items">
    {% for key, child in children %}
      {% if key|first != '#' %}
        <div class="entityqueue-widget-item">
          {{ child }}
        </div>
      {% endif %}
    {% endfor %}
  </div>
</div>
```

### Example 2: Grid Layout

```twig
<fieldset class="entityqueue-form-widget entityqueue-form-widget--grid"{{ attributes }}>
  {% if legend_title %}
    <legend class="entityqueue-form-widget__legend">
      {{ legend_title }}
    </legend>
  {% endif %}

  <div class="entityqueue-form-widget__items entityqueue-form-widget__items--grid">
    {% for key, child in children %}
      {% if key|first != '#' %}
        <div class="entityqueue-form-widget__item">
          {{ child }}
        </div>
      {% endif %}
    {% endfor %}
  </div>
</fieldset>
```

### Example 3: With Help Text

```twig
<fieldset class="entityqueue-widget"{{ attributes }}>
  {% if legend_title %}
    <legend class="entityqueue-widget__legend">
      <strong>{{ legend_title }}</strong>
      <span class="entityqueue-widget__required" title="{{ 'Required' }}">*</span>
    </legend>
  {% endif %}

  <div class="entityqueue-widget__description">
    <p>
      {{ 'Choose which featured sections this content should appear in:'|t }}
    </p>
  </div>

  <div class="entityqueue-widget__items">
    {% for key, child in children %}
      {% if key|first != '#' %}
        {% set queue_name = key|replace('_', ' ')|capitalize %}
        <div class="entityqueue-widget__item">
          <label class="entityqueue-widget__label">
            {{ child }}
            <span class="entityqueue-widget__queue-name">{{ queue_name }}</span>
          </label>
          <small class="entityqueue-widget__hint">
            {% if 'featured' in key|lower %}
              {{ 'This queue has a limit of 5 items'|t }}
            {% endif %}
          </small>
        </div>
      {% endif %}
    {% endfor %}
  </div>
</fieldset>
```

## CSS Styling

### Default CSS Classes

```css
/* Widget container */
.entityqueue-form-widget
.form-section--entityqueues

/* Individual items */
.form-item--entityqueue
.entityqueue-widget-item

/* Checkbox label */
.entityqueue-widget__label

/* Legend/title */
.fieldset__legend
```

### CSS Examples

#### Basic Styling

```css
/* Widget container */
.entityqueue-form-widget {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 20px;
  background-color: #f9f9f9;
}

/* Widget title */
.entityqueue-form-widget .fieldset__legend {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 10px;
}

/* Individual queue item */
.entityqueue-widget-item {
  margin-bottom: 10px;
  padding: 8px;
}

/* Queue checkbox */
.entityqueue-widget-item input[type="checkbox"] {
  margin-right: 8px;
}

/* Queue label */
.entityqueue-widget-item label {
  display: flex;
  align-items: center;
  cursor: pointer;
}
```

#### Grid Layout

```css
.entityqueue-form-widget--grid .entityqueue-widget-items {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.entityqueue-form-widget--grid .entityqueue-widget-item {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: white;
  transition: background-color 0.2s;
}

.entityqueue-form-widget--grid .entityqueue-widget-item:hover {
  background-color: #f5f5f5;
}

.entityqueue-form-widget--grid .entityqueue-widget-item input:checked ~ label {
  font-weight: bold;
  color: #0066cc;
}
```

#### Responsive Design

```css
/* Large screens */
@media (min-width: 1024px) {
  .entityqueue-widget-items {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Tablets */
@media (min-width: 768px) and (max-width: 1023px) {
  .entityqueue-widget-items {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Mobile */
@media (max-width: 767px) {
  .entityqueue-widget-items {
    display: block;
  }

  .entityqueue-widget-item {
    margin-bottom: 10px;
  }
}
```

## JavaScript Integration

### Attach Behaviors to Widget

**Location**: `your-theme/js/entityqueue-widget.js`

```javascript
(function (Drupal) {
  'use strict';

  Drupal.behaviors.entityqueueWidgetCustom = {
    attach: function (context, settings) {
      // Find all entityqueue checkboxes
      const checkboxes = context.querySelectorAll(
        '.entityqueue-widget input[type="checkbox"]'
      );

      checkboxes.forEach(checkbox => {
        // Add custom behavior on change
        checkbox.addEventListener('change', function() {
          const queueId = this.name;
          const isChecked = this.checked;

          // Custom behavior
          if (isChecked) {
            this.closest('.entityqueue-widget-item')
              .classList.add('is-selected');
            Drupal.announce('Added to queue: ' + queueId);
          } else {
            this.closest('.entityqueue-widget-item')
              .classList.remove('is-selected');
            Drupal.announce('Removed from queue: ' + queueId);
          }
        });
      });
    }
  };
})(Drupal);
```

### Add to Theme Libraries

**File**: `your-theme/your-theme.libraries.yml`

```yaml
entityqueue-widget:
  version: 1.0
  js:
    js/entityqueue-widget.js: {}
  css:
    theme:
      css/entityqueue-widget.css: {}
```

### Attach Library in Template

```twig
{# In entityqueue-form-widget.html.twig #}
{{ attach_library('your_theme/entityqueue-widget') }}

<fieldset{{ attributes }}>
  {# ... template content ... #}
</fieldset>
```

## Accessibility

### ARIA Labels

```twig
<fieldset class="entityqueue-widget" aria-label="{{ legend_title }}">
  <legend>{{ legend_title }}</legend>

  <div class="entityqueue-widget-items" role="group">
    {% for key, child in children %}
      {% if key|first != '#' %}
        <div class="entityqueue-widget-item">
          <input
            type="checkbox"
            id="{{ id }}-{{ key }}"
            name="{{ name }}[{{ key }}]"
            aria-label="Add to {{ queue_label }}"
            aria-describedby="{{ id }}-{{ key }}-description"
          />
          <label for="{{ id }}-{{ key }}">{{ queue_label }}</label>
          <small id="{{ id }}-{{ key }}-description">
            {{ queue_description }}
          </small>
        </div>
      {% endif %}
    {% endfor %}
  </div>
</fieldset>
```

### Keyboard Navigation

**Ensure**:
- Tab key navigates through checkboxes
- Space/Enter toggles checkboxes
- Focus indicators visible
- No keyboard traps

### Screen Reader Support

**Attributes**:
```html
<fieldset role="group" aria-label="Content queues">
  <legend>Queues</legend>
  <input
    type="checkbox"
    aria-label="Add to Featured Articles queue"
    aria-describedby="featured-help"
  />
  <span id="featured-help">Select to feature on homepage</span>
</fieldset>
```

## Theme Integration Checklist

Before deploying theme changes:

- [ ] Templates override correctly
- [ ] CSS classes properly styled
- [ ] Grid/responsive layout works
- [ ] Checkboxes remain functional
- [ ] Keyboard navigation works
- [ ] Mobile/tablet view responsive
- [ ] Colors accessible (contrast)
- [ ] Labels descriptive for screen readers
- [ ] JavaScript behaviors attach correctly
- [ ] No console errors

## Common Customization Scenarios

### Scenario 1: Change Queue Display to Buttons

```twig
<div class="entityqueue-buttons">
  {% for key, child in children %}
    {% if key|first != '#' %}
      <button
        type="button"
        class="entityqueue-button"
        aria-pressed="{{ child['#checked'] ? 'true' : 'false' }}"
        data-queue="{{ key }}"
      >
        {{ child['#title'] }}
      </button>
    {% endif %}
  {% endfor %}
</div>
```

With JavaScript:
```javascript
document.querySelectorAll('.entityqueue-button').forEach(btn => {
  btn.addEventListener('click', function() {
    const checkbox = document.querySelector(`input[name*="${this.dataset.queue}"]`);
    checkbox.checked = !checkbox.checked;
    checkbox.dispatchEvent(new Event('change'));
  });
});
```

### Scenario 2: Conditional Display by Role

```php
// In your theme's theme file
function your_theme_theme_suggestions_entityqueue_form_widget_alter(&$suggestions, $variables) {
  $user = \Drupal::currentUser();

  if ($user->hasRole('editor')) {
    $suggestions[] = 'entityqueue_form_widget__editor';
  }

  return $suggestions;
}
```

Then create: `templates/form/entityqueue-form-widget--editor.html.twig`

### Scenario 3: Add Icons to Queues

```twig
<div class="entityqueue-widgets-with-icons">
  {% for key, child in children %}
    {% if key|first != '#' %}
      <div class="entityqueue-item-with-icon">
        <span class="entityqueue-icon" aria-hidden="true">
          {% if 'featured' in key|lower %}
            ⭐
          {% elseif 'trending' in key|lower %}
            📈
          {% else %}
            📌
          {% endif %}
        </span>
        <label>
          {{ child }}
          <span>{{ child['#title'] }}</span>
        </label>
      </div>
    {% endif %}
  {% endfor %}
</div>
```

## Next Steps

- [Customization Guide](0-customization.md)
- [Module Architecture](2-architecture.md)
- [API Reference](1-api-reference.md)
