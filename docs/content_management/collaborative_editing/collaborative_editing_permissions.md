---
description: Configure user permissions and access control for collaborative editing features.
---

# Collaborative editing permissions

Collaborative editing integrates with [[= product_name =]]'s role-based permission system to provide fine-grained access control over collaboration features.

## Overview

Collaborative editing permissions work on two levels:

1. **Feature permissions**: Control who can use collaboration features
2. **Content permissions**: Respect existing content access controls

All collaboration activities respect the underlying content permissions, ensuring users can only collaborate on content they already have access to.

## Collaboration policies

### Core policies

The collaboration module introduces several new policies:

| Policy | Function | Description |
|--------|----------|-------------|
| `collaboration` | `create_session` | Create new collaboration sessions |
| `collaboration` | `participate` | Join and participate in sessions |
| `collaboration` | `invite` | Invite other users to sessions |
| `collaboration` | `manage_sessions` | Manage any collaboration session |
| `collaboration` | `view_shared` | View shared drafts dashboard |
| `collaboration` | `real_time_edit` | Use real-time editing features |

### Invitation-specific policies

| Policy | Function | Description |
|--------|----------|-------------|
| `collaboration` | `invite_internal` | Invite internal users |
| `collaboration` | `invite_external` | Invite external users via email |
| `collaboration` | `invite_groups` | Invite entire user groups |

### Session management policies

| Policy | Function | Description |
|--------|----------|-------------|
| `collaboration` | `end_session` | End collaboration sessions |
| `collaboration` | `modify_permissions` | Change participant permissions |
| `collaboration` | `extend_session` | Extend session duration |
| `collaboration` | `export_data` | Export collaboration data |

## Policy configuration examples

### Basic collaboration user

A typical content editor who can participate in collaboration:

```yaml
# config/packages/security.yaml
role_editor_collaboration:
    policies:
        - module: collaboration
          function: create_session
        - module: collaboration
          function: participate
        - module: collaboration
          function: invite_internal
        - module: collaboration
          function: view_shared
        - module: content
          function: read
        - module: content
          function: edit
```

### Advanced collaboration manager

A user who can manage collaboration sessions for their team:

```yaml
role_collaboration_manager:
    policies:
        - module: collaboration
          function: manage_sessions
          limitations:
            - identifier: User_Group
              values: [12, 13]  # Specific user groups
        - module: collaboration
          function: invite_external
        - module: collaboration
          function: modify_permissions  
        - module: collaboration
          function: end_session
        - module: collaboration
          function: export_data
```

### External collaboration user (limited)

Restricted permissions for external collaborators:

```yaml
role_external_collaborator:
    policies:
        - module: collaboration
          function: participate
          limitations:
            - identifier: Content_Type
              values: ['article', 'blog_post']
        - module: collaboration
          function: view_shared
        - module: content
          function: read
          limitations:
            - identifier: Section
              values: [2]  # Public section only
```

## Limitations

### User Group limitation

Restrict collaboration to specific user groups:

```yaml
policies:
    - module: collaboration
      function: invite
      limitations:
        - identifier: User_Group
          values: [4, 5, 6]  # Marketing, Editorial, Design teams
```

### Content Type limitation

Limit collaboration to specific content types:

```yaml
policies:
    - module: collaboration
      function: create_session
      limitations:
        - identifier: Content_Type
          values: ['article', 'blog_post', 'landing_page']
```

### Section limitation

Restrict collaboration to content in specific sections:

```yaml
policies:
    - module: collaboration
      function: participate
      limitations:
        - identifier: Section
          values: [1, 2]  # Standard and Media sections
```

### Time-based limitations

Configure time-based restrictions (configured at system level):

```yaml
# config/packages/collaboration.yaml
ibexa:
    system:
        default:
            collaboration:
                limitations:
                    session_duration:
                        default: P1D  # 1 day
                        external_users: PT4H  # 4 hours for external users
                    daily_sessions:
                        max_per_user: 5
                        max_per_content: 3
```

## Permission inheritance

### Content permissions

Collaborative editing respects existing content permissions:

- **Read access**: Users must have `content/read` for the content being collaborated on
- **Edit access**: To make changes, users need `content/edit` permissions
- **Publish access**: Only users with `content/publish` can finalize collaborative changes

### Location-based permissions

Content location permissions apply to collaboration:

```yaml
policies:
    - module: content
      function: read
      limitations:
        - identifier: Subtree
          values: ['/1/2/']  # Media subtree
    - module: collaboration
      function: participate  # Can only collaborate on content in Media subtree
```

### Language permissions

Multi-language content collaboration respects language limitations:

```yaml
policies:
    - module: content
      function: edit
      limitations:
        - identifier: Language
          values: ['eng-US', 'fre-FR']
    - module: collaboration
      function: create_session  # Can collaborate on English and French content
```

## External user permissions

### Configuration

Configure external user access in system settings:

```yaml
# config/packages/collaboration.yaml
ibexa:
    system:
        default:
            collaboration:
                external_users:
                    enabled: true
                    max_session_duration: PT4H
                    allowed_content_types: ['article', 'blog_post']
                    restricted_fields: ['internal_notes', 'seo_metadata']
                    require_terms_acceptance: true
```

### External user role template

Create a template role for external users:

```yaml
role_external_template:
    policies:
        - module: collaboration
          function: participate
          limitations:
            - identifier: Content_Type
              values: ['article']
        - module: content
          function: read
          limitations:
            - identifier: Section
              values: [2]  # Public section only
```

### Email domain restrictions

Restrict external invitations to specific email domains:

```yaml
ibexa:
    system:
        default:
            collaboration:
                external_users:
                    allowed_domains: ['partner.com', 'contractor.org']
                    blocked_domains: ['competitor.com']
```

## Session-level permissions

### Participant roles

Each collaboration session can have different participant roles:

| Role | Permissions | Use Case |
|------|-------------|----------|
| **Owner** | Full control over session | Session creator |
| **Admin** | Manage participants, end session | Team leads |
| **Editor** | Edit content, comment | Content creators |
| **Reviewer** | View, comment, approve/reject | Reviewers |
| **Viewer** | View only, comment | Stakeholders |

### Dynamic permission assignment

Assign permissions when inviting users:

```php
// In your invitation code
$invitation = $collaborationService->createInvitation($session, [
    'user_id' => $userId,
    'role' => 'editor',
    'permissions' => [
        'edit' => true,
        'comment' => true,
        'invite_others' => false,
        'end_session' => false,
    ],
    'expiration' => new \DateTime('+7 days'),
]);
```

## Permission checking examples

### Check if user can create sessions

```php
use Ibexa\Contracts\Core\Repository\PermissionResolver;

class CollaborationPermissionChecker
{
    public function canCreateSession(User $user, Content $content): bool
    {
        return $this->permissionResolver->hasAccess('collaboration', 'create_session') &&
               $this->permissionResolver->canUser('content', 'edit', $content);
    }
}
```

### Check collaboration permissions in templates

```twig
{% if ibexa_is_granted('collaboration', 'create_session') %}
    <button class="btn btn-primary" id="start-collaboration">
        Start Collaboration
    </button>
{% endif %}

{% if ibexa_is_granted('collaboration', 'invite_external') %}
    <input type="email" placeholder="Invite external user...">
{% endif %}
```

### Verify session access

```php
public function canAccessSession(User $user, CollaborationSession $session): bool
{
    // Check if user is participant
    if ($session->isParticipant($user)) {
        return true;
    }
    
    // Check if user has manage_sessions permission
    if ($this->permissionResolver->hasAccess('collaboration', 'manage_sessions')) {
        return true;
    }
    
    // Check if user has access to the content
    return $this->permissionResolver->canUser('content', 'read', $session->getContent());
}
```

## Security considerations

### Session security

- **Token-based access**: Each session uses unique tokens for participant authentication
- **IP restrictions**: Optionally restrict sessions to specific IP addresses
- **Device limits**: Limit number of concurrent devices per user
- **Audit logging**: All collaboration activities are logged for security review

### Data protection

- **Content isolation**: Users can only access content they have permissions for
- **Change attribution**: All modifications are attributed to specific users
- **Export restrictions**: Control who can export collaboration data
- **Cleanup policies**: Automatic cleanup of expired sessions and data

### External user security

- **Limited access**: External users have restricted permissions by default
- **Time limits**: Enforce shorter session durations for external users
- **Content restrictions**: Limit which content types external users can access
- **Email verification**: Require email verification for external participants

## Troubleshooting permissions

### Common permission issues

**Cannot start collaboration**
```
Check: collaboration/create_session policy
Check: content/edit permission on target content
Check: User is not exceeding session limits
```

**Cannot invite users**
```
Check: collaboration/invite_internal or collaboration/invite_external policy
Check: Target users have necessary content permissions
Check: Email domain restrictions for external users
```

**Session access denied**
```
Check: Session is still active (not expired)
Check: User is valid participant or has manage_sessions policy
Check: Content permissions still valid
```

### Debug permission issues

Use the debug commands to troubleshoot:

```bash
# Check user permissions
php bin/console debug:permission collaboration create_session --user=john

# List all collaboration policies
php bin/console debug:permission --module=collaboration

# Check content permissions
php bin/console debug:permission content edit --content-id=123
```

### Enable permission logging

Add detailed logging for permission checks:

```yaml
# config/packages/monolog.yaml
monolog:
    handlers:
        collaboration_permissions:
            type: stream
            path: '%kernel.logs_dir%/collaboration_permissions.log'
            channels: ['collaboration_permissions']
            level: debug
```

## Best practices

### Role design

- **Principle of least privilege**: Grant minimum necessary permissions
- **Clear role hierarchy**: Define clear relationships between roles
- **Regular review**: Periodically review and update permission assignments
- **Documentation**: Document custom roles and their intended use cases

### External user management

- **Time limits**: Use shorter session durations for external users
- **Content restrictions**: Limit external access to public or shared content
- **Review requirements**: Require internal review of external user contributions
- **Terms and conditions**: Require acceptance of collaboration terms

### Monitoring and auditing

- **Regular audits**: Review collaboration activity logs regularly
- **Permission changes**: Monitor changes to collaboration policies
- **Session analytics**: Track session usage and participation patterns
- **Security alerts**: Set up alerts for suspicious collaboration activity

## Next steps

- [Learn how to use collaborative editing](collaborative_editing_usage.md)
- [Explore the PHP API](collaborative_editing_api.md)
- [Set up custom events and notifications](collaborative_editing_events.md)