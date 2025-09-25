# Repository 

## Project Overview

This is the source repository for the Ibexa DXP Developer Documentation, a comprehensive documentation site for Ibexa DXP - a digital experience platform built on the Symfony Full Stack Framework in PHP. The documentation is built using MkDocs with Material theme and includes code samples, tutorials, API references, and guides.

## Build and Development Commands

### Documentation Building
```bash
# Install Python dependencies
pip install -r requirements.txt

# Serve documentation locally with live reload
mkdocs serve
# Documentation available at http://localhost:8000

# Build static documentation
mkdocs build
# Alternative build command (from user's global CLAUDE.md):
~/python/bin/python3.13 -m mkdocs build
```

### Code Quality and Testing
```bash
# Install PHP dependencies for code sample testing
composer update

# Run PHPStan on code samples
composer phpstan
# Alternative: phpstan analyse

# Fix code style
composer fix-cs
# Alternative: php-cs-fixer fix --config=.php-cs-fixer.php -v --show-progress=dots

# Check code style (dry run)
composer check-cs
# Alternative: php-cs-fixer fix --config=.php-cs-fixer.php -v --show-progress=dots --dry-run
```

### REST API Reference Generation
```bash
# Generate HTML from RAML files
php tools/raml2html/raml2html.php build --non-standard-http-methods=COPY,MOVE,PUBLISH,SWAP -t default -o docs/api/rest_api/rest_api_reference/output/ docs/api/rest_api/rest_api_reference/input/ibexa.raml

# Move generated file to proper location
# Move rest_api_reference.html from output folder to docs/api/rest_api/rest_api_reference/
```

## Architecture and Structure

### Documentation Organization
- **`docs/`**: Main documentation content organized by feature areas:
  - `getting_started/`: Installation and initial setup guides
  - `api/`: PHP API, REST API, GraphQL, and event references
  - `content_management/`: Content modeling, field types, data migration
  - `templating/`: Twig templates, rendering, design engine
  - `pim/`: Product Information Management features
  - `commerce/`: E-commerce functionality (cart, checkout, payment)
  - `search/`: Search engines, criteria, aggregations
  - `permissions/`: Role-based access control
  - `users/`: User management and customer features
  - `personalization/`: Recommendation engine integration
  - `multisite/`: Multi-site and internationalization
  - `infrastructure_and_maintenance/`: Performance, caching, security
  - `update_and_migration/`: Version upgrade guides
  - `release_notes/`: Version-specific changes

### Code Samples Architecture
- **`code_samples/`**: Executable PHP code examples organized by feature
- **PHPStan Analysis**: All code samples are tested with PHPStan at level 8 for quality assurance
- **Dependency Management**: Code samples include real Ibexa DXP packages as dev dependencies to ensure examples work with actual APIs

### MkDocs Configuration
- **`mkdocs.yml`**: Main navigation and site configuration
- **`plugins.yml`**: Plugin configuration including macros, search, and redirects
- **Jinja2 Macros**: Custom syntax with `[[= =]]` for variables and `[[% %]]` for blocks
- **Material Theme**: Uses MkDocs Material with custom CSS and JavaScript

### Content Architecture Patterns

#### Documentation Structure
1. **Landing Pages**: Feature overviews with card-based navigation
2. **Product Guides**: Comprehensive explanations of concepts and capabilities
3. **Installation Guides**: Step-by-step setup instructions
4. **API References**: Auto-generated PHP API docs and manually maintained references
5. **Code Examples**: Both inline examples and separate executable samples

#### Content Types
- **Tutorials**: Step-by-step learning experiences
- **Guides**: Task-oriented instructions
- **References**: Lookup information (API, configuration, etc.)
- **Explanations**: Concept-oriented background information

#### Metadata and Front Matter
All documentation files use YAML front matter with:
- `description`: Brief page description for SEO
- `editions`: Which Ibexa editions support the feature
- `month_change`: Boolean indicating if content was added this month
- `page_type`: Categorization (e.g., `landing_page`, `reference`)

### Content Management Workflow

#### Feature Documentation Process
When documenting new features, follow this comprehensive pattern:
1. **Core Documentation**: Landing page, guide, installation docs
2. **API Documentation**: Events, search criteria, sort clauses
3. **Code Examples**: PHP samples and data migration YAML examples
4. **System Integration**: Update navigation, permissions, cross-references
5. **Supporting Materials**: Screenshots, related feature updates

#### Content Validation
- **Link Validation**: MkDocs validates internal links and anchors
- **Code Quality**: PHPStan ensures code samples are syntactically correct
- **Style Consistency**: PHP-CS-Fixer maintains code formatting standards

## Development Patterns

### New Feature Documentation
Follow the established pattern seen in recent additions like the Discounts feature:
- Create feature-specific directory under `docs/`
- Add comprehensive guide explaining concepts, capabilities, and use cases
- Include installation and configuration instructions
- Document API events and search functionality
- Provide executable code examples in `code_samples/`
- Update navigation in `mkdocs.yml`
- Cross-reference from related features

### Code Sample Guidelines
- All PHP code must pass PHPStan level 8 analysis
- Use real Ibexa DXP APIs and services from the dev dependencies
- Organize samples by feature area matching documentation structure
- Include both basic usage and advanced implementation examples
- Follow Ibexa coding standards (enforced by PHP-CS-Fixer)

### Content Linking and Cross-References
- Use relative paths for internal links: `[link text](../path/to/file.md)`
- Reference PHP APIs with full namespaces and link to generated API docs
- Include file:line references for code locations: `src/Service.php:123`
- Maintain redirect maps in `plugins.yml` for moved content

### Asset Management
- Screenshots and images go in feature-specific `img/` directories
- Use descriptive filenames following the pattern: `feature_context_description.png`
- Optimize images for web delivery
- Include alt text for accessibility

This repository represents a complex, multi-faceted documentation system that balances comprehensive coverage with maintainability through automated testing and clear organizational patterns.
