# Contributing to SuperAuth

Thank you for your interest in contributing to SuperAuth! We welcome contributions from the community and appreciate your help in making SuperAuth better.

## 🤝 How to Contribute

### Reporting Issues

Before creating an issue, please:

1. **Search existing issues** to avoid duplicates
2. **Check the documentation** to ensure it's not a configuration issue
3. **Provide detailed information** including:
   - SuperAuth version
   - Laravel version
   - PHP version
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if applicable)

### Suggesting Features

We welcome feature suggestions! Please:

1. **Check existing issues** to avoid duplicates
2. **Describe the feature** clearly and concisely
3. **Explain the use case** and benefits
4. **Provide examples** if possible

### Code Contributions

#### Getting Started

1. **Fork the repository**
2. **Clone your fork**:
   ```bash
   git clone https://github.com/your-username/superauth.git
   cd superauth
   ```

3. **Install dependencies**:
   ```bash
   composer install
   ```

4. **Run tests** to ensure everything works:
   ```bash
   composer test
   ```

#### Development Workflow

1. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes** following our coding standards

3. **Write tests** for your changes

4. **Run the test suite**:
   ```bash
   composer test
   composer check
   ```

5. **Commit your changes**:
   ```bash
   git commit -m "feat: add your feature description"
   ```

6. **Push to your fork**:
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**

## 📋 Coding Standards

### PHP Code Style

We follow PSR-12 coding standards:

```bash
# Run code style checks
composer check:style

# Fix code style issues
composer fix:style
```

### Code Quality

We use PHPStan for static analysis:

```bash
# Run static analysis
composer check:quality
```

### Testing

All new features must include tests:

```bash
# Run all tests
composer test

# Run specific test suite
vendor/bin/phpunit tests/Feature/YourTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

## 🏗️ Development Guidelines

### Code Structure

- **Services**: Business logic should be in service classes
- **Models**: Use Eloquent models for data access
- **Livewire Components**: Frontend logic in Livewire components
- **Middleware**: Security and access control in middleware
- **Tests**: Comprehensive test coverage for all features

### Documentation

- **Code Comments**: Document complex logic
- **README Updates**: Update README for new features
- **Changelog**: Add entries to CHANGELOG.md
- **API Documentation**: Document new API endpoints

### Security

- **Input Validation**: Always validate user input
- **SQL Injection**: Use parameterized queries
- **XSS Protection**: Escape output properly
- **CSRF Protection**: Include CSRF tokens
- **Rate Limiting**: Implement rate limiting where appropriate

## 🧪 Testing Guidelines

### Test Structure

```php
<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_works_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act
        $result = $this->performAction($user);
        
        // Assert
        $this->assertTrue($result);
    }
}
```

### Test Categories

- **Unit Tests**: Test individual methods and functions
- **Feature Tests**: Test complete features and workflows
- **Integration Tests**: Test component interactions
- **Security Tests**: Test security features and vulnerabilities

### Test Coverage

- **Aim for 100% coverage** for new features
- **Test edge cases** and error conditions
- **Test security features** thoroughly
- **Test performance** for critical paths

## 📝 Commit Guidelines

### Commit Message Format

We use conventional commits:

```
type(scope): description

[optional body]

[optional footer]
```

### Types

- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes
- **refactor**: Code refactoring
- **test**: Test additions/changes
- **chore**: Maintenance tasks

### Examples

```
feat(auth): add OTP verification system
fix(security): resolve password breach check issue
docs(readme): update installation instructions
test(ai): add AI agent test coverage
```

## 🔄 Pull Request Process

### Before Submitting

1. **Ensure tests pass**:
   ```bash
   composer test
   ```

2. **Check code style**:
   ```bash
   composer check
   ```

3. **Update documentation** if needed

4. **Add changelog entry** for significant changes

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass
- [ ] New tests added
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] Changelog updated
```

### Review Process

1. **Automated checks** must pass
2. **Code review** by maintainers
3. **Testing** by maintainers
4. **Approval** from at least one maintainer

## 🐛 Bug Reports

### Bug Report Template

```markdown
## Bug Description
Clear description of the bug

## Steps to Reproduce
1. Step one
2. Step two
3. Step three

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Environment
- SuperAuth Version:
- Laravel Version:
- PHP Version:
- OS:

## Additional Context
Any other relevant information
```

## 💡 Feature Requests

### Feature Request Template

```markdown
## Feature Description
Clear description of the feature

## Use Case
Why is this feature needed?

## Proposed Solution
How should this feature work?

## Alternatives Considered
Other approaches you've considered

## Additional Context
Any other relevant information
```

## 🏷️ Release Process

### Version Numbering

We follow [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

### Release Checklist

- [ ] All tests pass
- [ ] Documentation updated
- [ ] Changelog updated
- [ ] Version tagged
- [ ] Release notes prepared

## 📞 Getting Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and discussions
- **Email**: [team@superauth.dev](mailto:team@superauth.dev)

## 🙏 Recognition

Contributors will be recognized in:

- **README.md** contributors section
- **CHANGELOG.md** for significant contributions
- **Release notes** for major contributions

## 📄 License

By contributing to SuperAuth, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to SuperAuth! 🎉
