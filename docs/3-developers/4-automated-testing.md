# Automated testing

The module ships two complementary layers of automated tests plus a reusable
test fixture recipe.

| Layer | Tooling | Location |
| --- | --- | --- |
| Functional acceptance (BDD) | [webship-js](https://webship.co) (Playwright + Cucumber-js) | `tests/functional-acceptance/` |
| Unit | PHPUnit | `tests/src/Unit/` |
| Test fixtures | Drupal recipe | `tests/recipes/entityqueue_form_widget_test/` |

## Test fixture recipe

`tests/recipes/entityqueue_form_widget_test` is an applicable Drupal recipe that
creates everything the browser tests need: the `test_content` and
`queued_content` content types, a Body field, and three entityqueues
(`test_queue`, `test_limited_queue` and `test_multiple_subqueues`). It replaces
the former `entityqueue_form_widget_test` module.

Apply it to an installed site with:

```bash
drush recipe web/modules/contrib/entityqueue_form_widget/tests/recipes/entityqueue_form_widget_test
```

## Functional acceptance suite (webship-js)

The `.feature` files under `tests/functional-acceptance/features/` describe the
[usage scenarios](../1-users/4-usage-scenarios.md) in plain-English Gherkin and
run them in a real Chromium browser.

To run the suite locally:

```bash
# 1. From the Drupal project root, reset the site under test:
#    fresh install, apply the recipe, seed the test users and content.
bash web/modules/contrib/entityqueue_form_widget/tests/functional-acceptance/scripts/reset-site.sh

# 2. Serve the site (any web server works); e.g. the built-in PHP server:
php -S 0.0.0.0:8899 -t web web/.ht.router.php &

# 3. Install the Node dependencies and run the suite.
cd web/modules/contrib/entityqueue_form_widget/tests/functional-acceptance
npm install
npx playwright install --with-deps chromium
LAUNCH_URL=http://localhost:8899 BROWSER=chromium npm test
```

The suite seeds two users, both with the password `password`:

- `eqfw_editor` — a privileged editor who may manipulate all queues.
- `eqfw_basic` — an editor with no entityqueue permissions (used by the
  permission-gating scenario).

An HTML report is written to `tests/functional-acceptance/tests/reports/` after
each run.

## Unit tests

The PHPUnit unit tests in `tests/src/Unit/` cover the pure logic of the hook
service (`getAllowedSubqueueList()` bundle and target-type filtering) with fully
mocked dependencies, so they run without a database:

```bash
vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/modules/contrib/entityqueue_form_widget/tests/src/Unit
```

## Continuous integration

The GitLab CI pipeline (`.gitlab-ci.yml`) runs, on every merge request:

- the standard Drupal CI jobs — `composer-lint`, `cspell`, `eslint`, `phpcs`,
  `phpstan`;
- `phpunit` — the unit tests above;
- `webship` — installs Drupal on SQLite, applies the test recipe, seeds the
  test users, starts a PHP web server and runs the full webship-js acceptance
  suite headless in Chromium.

Every job runs with `allow_failure: false`.
