# Contributing to :package_name

Thank you for considering contributing to **:package_name**! We welcome contributions from the community.

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone git@github.com:your-username/:package_repo.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feat/your-feature`

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

## Quality Checks

Before submitting a PR, ensure all checks pass:

```bash
# Run tests
composer test

# Run static analysis
composer analyse

# Check code style
composer format-test

# Fix code style
composer format
```

## Pull Request Process

1. Ensure your branch is up to date with `main`
2. All CI checks must pass
3. At least one maintainer must approve
4. PRs are squash-merged — the PR title becomes the commit message

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) with PSR-12. Run `composer format` to auto-fix.

## Reporting Bugs

Use the [bug report template](https://github.com/LaraArabDev/:package_repo/issues/new?template=bug_report.yml).

## Suggesting Features

Use the [feature request template](https://github.com/LaraArabDev/:package_repo/issues/new?template=feature_request.yml).
