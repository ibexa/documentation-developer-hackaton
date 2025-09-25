---
description: Install and configure the collaborative editing feature in Ibexa DXP.
---

# Collaborative editing installation

The collaborative editing feature is available as part of [[= product_name =]] v5.0+ and requires the `ibexa/collaboration` package.

## Requirements

- [[= product_name =]] v5.0 or higher
- PHP 8.2 or higher
- Symfony 6.4+ or 7.0+
- Database: MySQL 8.0+ or PostgreSQL 12+
- Redis (recommended for real-time features)

## Installation

### 1. Install the collaboration package

The `ibexa/collaboration` package is included by default in [[= product_name =]] v5.0+ Experience and Commerce editions.

For manual installation:

```bash
composer require ibexa/collaboration
```

### 2. Enable the bundle

Add the collaboration bundle to your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ibexa\Bundle\Collaboration\IbexaCollaborationBundle::class => ['all' => true],
];
```

### 3. Configure the database

Run the database migration to create the necessary tables:

```bash
php bin/console doctrine:migrations:migrate --configuration=vendor/ibexa/collaboration/src/bundle/Resources/config/migrations.yml
```

This creates the following tables:
- `ibexa_collaboration_sessions` - Collaboration session data
- `ibexa_collaboration_participants` - Session participants
- `ibexa_collaboration_invitations` - Invitation management

### 4. Configure Redis (optional but recommended)

For real-time editing features, configure Redis:

```yaml
# config/packages/redis.yaml
framework:
    cache:
        app: cache.adapter.redis
        default_redis_provider: 'redis://localhost:6379'

ibexa:
    system:
        default:
            collaboration:
                real_time:
                    enabled: true
                    redis_dsn: 'redis://localhost:6379'
```

### 5. Configure WebSocket support (for real-time editing)

Install and configure a WebSocket server for real-time features:

```bash
composer require reactphp/socket ratchet/pawl
```

Add WebSocket configuration:

```yaml
# config/packages/collaboration.yaml
ibexa:
    system:
        default:
            collaboration:
                websocket:
                    enabled: true
                    host: 'localhost'
                    port: 8080
                    path: '/collaboration'
```

### 6. Configure email notifications

Set up email templates and sender configuration:

```yaml
# config/packages/collaboration.yaml
ibexa:
    system:
        default:
            collaboration:
                notifications:
                    email:
                        enabled: true
                        from_email: 'noreply@example.com'
                        from_name: 'Your Site Name'
                        templates:
                            invitation: '@IbexaCollaboration/emails/invitation.html.twig'
                            session_started: '@IbexaCollaboration/emails/session_started.html.twig'
```

## Configuration options

### Basic configuration

```yaml
# config/packages/collaboration.yaml
ibexa:
    system:
        default:
            collaboration:
                enabled: true
                
                # Session management
                sessions:
                    max_duration: P1D  # 1 day
                    cleanup_interval: PT1H  # 1 hour
                    max_participants: 10
                
                # Invitation settings
                invitations:
                    expiration_time: P7D  # 7 days
                    external_users_enabled: true
                    require_confirmation: true
                
                # Dashboard integration
                dashboard:
                    enabled: true
                    items_per_page: 20
```

### Real-time editing configuration

```yaml
ibexa:
    system:
        default:
            collaboration:
                real_time:
                    enabled: true
                    sync_interval: 1000  # milliseconds
                    conflict_resolution: 'last_write_wins'
                    cursor_timeout: 30000  # milliseconds
```

### Security settings

```yaml
ibexa:
    system:
        default:
            collaboration:
                security:
                    # External user restrictions
                    external_users:
                        max_session_duration: PT4H  # 4 hours
                        allowed_content_types: ['article', 'blog_post']
                        restricted_fields: ['internal_notes']
                    
                    # Session security
                    require_https: true
                    session_token_expiry: PT2H  # 2 hours
```

## Permissions setup

### Grant collaboration permissions

Add the necessary policies to user roles:

```yaml
# In your role configuration
policies:
    - module: collaboration
      function: create_session
    - module: collaboration
      function: participate
    - module: collaboration
      function: invite
      limitations:
        - identifier: User_Group
          values: [4, 5]  # Restrict to specific user groups
    - module: collaboration
      function: manage_sessions
```

Available collaboration policies:
- `collaboration/create_session` - Create new collaboration sessions
- `collaboration/participate` - Participate in sessions
- `collaboration/invite` - Invite other users
- `collaboration/manage_sessions` - Manage any collaboration session
- `collaboration/view_shared` - View shared drafts

### Content-specific permissions

Collaborative editing respects existing content permissions. Users need:
- `content/read` for the content being collaborated on
- `content/edit` to make changes (if editing is allowed)
- `content/publish` to publish collaborative changes

## Testing the installation

### 1. Verify bundle installation

Check that the bundle is properly loaded:

```bash
php bin/console debug:container ibexa.collaboration
```

### 2. Test database setup

Verify tables were created:

```sql
SHOW TABLES LIKE 'ibexa_collaboration_%';
```

### 3. Check permissions

Log into the back office and verify:
- New dashboard tabs appear: "My shared drafts" and "Drafts shared with me"
- Collaboration options appear in content editing interface
- Invitation options are available

### 4. Test basic functionality

1. Create or edit a content item
2. Look for "Share" or "Collaborate" buttons
3. Try inviting another user
4. Verify email notifications are sent

## Troubleshooting

### Common issues

**Bundle not found error**
```
Bundle "IbexaCollaborationBundle" not found
```
Solution: Ensure the bundle is properly added to `config/bundles.php`

**Database migration failures**
```
Migration failed: Table already exists
```
Solution: Check if tables already exist and run migrations individually

**WebSocket connection issues**
```
WebSocket connection failed
```
Solution: Verify Redis is running and WebSocket server is configured correctly

**Permission denied errors**
```
Access denied to collaboration feature
```
Solution: Check user roles have the necessary collaboration policies

### Debug commands

Enable debug mode for collaboration:

```bash
php bin/console debug:config ibexa collaboration
```

View collaboration-related logs:

```bash
tail -f var/log/collaboration.log
```

Test Redis connection:

```bash
redis-cli ping
```

## Performance considerations

### Database optimization

Add indexes for better performance:

```sql
CREATE INDEX idx_collaboration_session_status ON ibexa_collaboration_sessions(status);
CREATE INDEX idx_collaboration_participant_user ON ibexa_collaboration_participants(user_id);
```

### Caching configuration

Enable proper caching for collaboration data:

```yaml
doctrine:
    orm:
        result_cache_driver: redis
        metadata_cache_driver: redis
        query_cache_driver: redis
```

## Next steps

- [Configure user permissions](collaborative_editing_permissions.md)
- [Learn how to use collaborative editing](collaborative_editing_usage.md)
- [Explore the PHP API](collaborative_editing_api.md)