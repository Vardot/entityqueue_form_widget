// Cucumber-js configuration for the Entityqueue Form Widget webship-js suite.
//
// Run from this directory (tests):
//   LAUNCH_URL=http://web:8899 BROWSER=chromium npm test
//
// The site under test must already have the
// tests/recipes/entityqueue_form_widget_test recipe applied (it ships the
// test users, roles, subqueues and fixture content as recipe content/config).
module.exports = {
  default: {
    // Cucumber step timeout must exceed Playwright's default 30s so the
    // friendly Why/Hint wrappers fire before cucumber's own timeout.
    timeout: 60000,
    // tsx/cjs lets cucumber-js load the .ts playwright.config with no build.
    requireModule: ['tsx/cjs'],
    require: [
      // webship-js core step definitions (hundreds of steps).
      'node_modules/webship-js/tests/step-definitions/**/*.js',
      // Project custom step definitions (login helper).
      'step-definitions/**/*.js',
    ],
    paths: ['features/**/*.feature'],
    format: [
      '@cucumber/pretty-formatter',
      // webship-js auto-generates the HTML report from tests/reports/.
      'json:reports/cucumber_report.json',
    ],
    worldParameters: {
      launchUrl: process.env.LAUNCH_URL || 'http://web:8899',
      minWaitTime: {
        page: 3000,
        before_scenario: 0,
        after_scenario: 0,
        before_step: 0,
        after_step: 0,
      },
    },
  },
};
