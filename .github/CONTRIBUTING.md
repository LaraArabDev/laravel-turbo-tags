# Contributing to :package_name

Thank you for considering contributing to **:package_name**! We welcome contributions from the community.

Please read our [Code of Conduct](CODE_OF_CONDUCT.md) before participating.

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone git@github.com:your-username/:package_repo.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feat/your-feature`
5. Run the test suite to ensure everything works: `composer test`

## Branch Naming

All branches must follow this pattern:

```
type/short-description
```

**Valid types:** `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`, `hotfix`, `release`

**Examples:**
- `feat/add-caching`
- `fix/null-pointer`
- `docs/update-readme`

## Commit Messages

This project follows [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): short description

Optional body with more details.
```

**Examples:**
- `feat: add tag caching support`
- `fix(model): resolve duplicate slug generation`
- `docs: update installation instructions`
- `test: add coverage for edge cases`

## PR Title

PR titles **must** follow the same conventional commit format as commit messages. This is enforced by CI.

## Acceptance Criteria

Every PR must meet **all** of the following before it can be merged:

### Required (all PRs)

- [ ] **All CI checks pass** — tests, PHPStan, Pint, security audit
- [ ] **Branch name** follows `type/short-description` convention
- [ ] **PR title** follows conventional commit format (`type: description`)
- [ ] **All commits** follow conventional commit format
- [ ] **No merge conflicts** with `main`
- [ ] **Reviewer checklist** is addressed (auto-posted on every PR)

### Code Changes

- [ ] **Tests included** — new/changed behavior has corresponding test coverage
- [ ] **No decrease in coverage** — coverage must stay the same or improve
- [ ] **PHPStan passes** at max level with no baseline additions
- [ ] **Code style** passes Laravel Pint (PSR-12)
- [ ] **No N+1 queries** introduced
- [ ] **No hardcoded values** that should be configurable
- [ ] **No sensitive data** exposed (keys, tokens, credentials)

### New Features

- [ ] **Feature is configurable** — can be enabled/disabled or customized via config
- [ ] **README updated** with usage examples
- [ ] **CHANGELOG updated** under `[Unreleased]`
- [ ] **Migration included** if schema changes are needed

### Bug Fixes

- [ ] **Regression test** included that reproduces the bug
- [ ] **Root cause** is addressed, not just symptoms
- [ ] **CHANGELOG updated** under `[Unreleased]`

### Breaking Changes

- [ ] **Justified and necessary** — no non-breaking alternative exists
- [ ] **Migration path documented** for existing users
- [ ] **CHANGELOG** clearly marks the breaking change
- [ ] **Commit/PR** includes `BREAKING CHANGE:` footer or `!` in type
- [ ] **Major version bump** will be triggered

### Documentation

- [ ] **Public API** changes are documented in README
- [ ] **Inline comments** explain non-obvious logic
- [ ] **PHPDoc blocks** on public methods

## Quality Checks

Before submitting a PR, ensure all checks pass locally:

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Check code style
composer format-test

# Fix code style
composer format
```

## Pull Request Process

1. Ensure your branch is up to date with `main`
2. Verify all acceptance criteria are met
3. All CI checks must pass (14 workflows)
4. Address the auto-generated reviewer checklist
5. At least one maintainer must approve
6. PRs are squash-merged — the PR title becomes the commit message

## Review Process

- PRs are reviewed within **48 hours** on business days
- Maintainers may request changes — please address them promptly
- Stale PRs (no activity for 30 days) are automatically closed
- Trivial fixes (typos, formatting) may be merged by a single maintainer
- Significant changes require at least two maintainer approvals

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) with PSR-12. Run `composer format` to auto-fix.

## Reporting Bugs

Use the [bug report template](https://github.com/LaraArabDev/:package_repo/issues/new?template=bug_report.yml).

## Suggesting Features

Use the [feature request template](https://github.com/LaraArabDev/:package_repo/issues/new?template=feature_request.yml).

## Security

See our [Security Policy](../SECURITY.md) for reporting vulnerabilities.
