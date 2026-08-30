# Governance

## Project Governance

**:package_name** is maintained by the [LaraArabDev](https://github.com/LaraArabDev) community.

### Roles

#### Maintainers

Maintainers have full access to the repository and are responsible for:

- Reviewing and merging pull requests
- Triaging issues and feature requests
- Releasing new versions
- Enforcing the Code of Conduct
- Setting the project's technical direction

#### Contributors

Anyone who submits a pull request, opens an issue, or participates in discussions is a contributor. Contributors are expected to follow the [Code of Conduct](CODE_OF_CONDUCT.md) and [Contributing Guide](CONTRIBUTING.md).

#### Reviewers

Trusted contributors who regularly provide high-quality reviews may be granted reviewer status. Reviewers can approve PRs but cannot merge without maintainer approval.

### Decision Making

- **Minor changes** (bug fixes, docs, small improvements): A single maintainer approval is sufficient
- **Major changes** (new features, breaking changes, architectural decisions): Require discussion in an issue/PR and at least two maintainer approvals
- **Breaking changes**: Must be documented in the CHANGELOG and follow semantic versioning

### Release Process

1. All CI checks must pass (tests, static analysis, code style, security)
2. CHANGELOG must be updated
3. Releases follow [Semantic Versioning](https://semver.org/)
4. Releases are automated via the release workflow when commits follow [Conventional Commits](https://www.conventionalcommits.org/)

### Conflict Resolution

1. Technical disagreements should be discussed in the relevant issue or PR
2. If consensus cannot be reached, maintainers make the final decision
3. Code of Conduct violations are handled by maintainers as described in the [Code of Conduct](CODE_OF_CONDUCT.md)
