const { Given } = require('@cucumber/cucumber');

/**
 * Custom login step for the Entityqueue Form Widget suite.
 *
 * The test users are seeded by scripts/reset-site.sh (drush) with a shared
 * password. Rather than a per-scenario UI login recipe we keep a small,
 * explicit map so scenarios read as business language: "Given I am logged in
 * as the "eqfw_editor" user".
 */
const USERS = {
  eqfw_editor: { name: 'eqfw_editor', pass: 'password' },
  eqfw_basic: { name: 'eqfw_basic', pass: 'password' },
};

/**
 * Log in through the standard Drupal /user/login form.
 *
 * Example #1: Given I am logged in as the "eqfw_editor" user
 * Example #2: Given we are logged in as the "eqfw_basic" user
 * Example #3: Given I am logged in as the "admin" user
 * Example #4: When I am logged in as the "eqfw_editor" user
 * Example #5: Given logged in as the "eqfw_editor" user
 */
Given(
  /^(?:I am |we are )?logged in as the "([^"]*)" user$/,
  async function (userKey) {
    const user = USERS[userKey] || { name: userKey, pass: 'password' };
    await this.page.goto(`${this.launchUrl}/user/login`, {
      waitUntil: 'domcontentloaded',
    });
    await this.page.fill('#edit-name', user.name);
    await this.page.fill('#edit-pass', user.pass);
    await this.page.click('#edit-submit');
    await this.page.waitForLoadState('domcontentloaded');
  },
);
