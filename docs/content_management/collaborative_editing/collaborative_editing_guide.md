---
description: Enable multiple users to collaborate on content creation, editing, and review through shared drafts, real-time editing, and invitation systems.
edition: experience,commerce
month_change: true
---

# Collaborative editing

Collaborative editing allows multiple users to work together on content creation and editing in [[= product_name =]]. 
This feature enables teams to streamline content workflows by sharing drafts, collaborating in real-time, and managing review processes efficiently.

## Key features

### Shared drafts

Content creators can share their draft content with internal team members or external collaborators. 
Shared drafts maintain version control while allowing multiple users to contribute to the same content item.

Key benefits:

- **Version control**: All changes are tracked and versioned
- **Access control**: Fine-grained permissions for different user types  
- **Dashboard integration**: Shared drafts appear in dedicated dashboard tabs
- **Workflow integration**: Seamless integration with existing content workflows

### Real-time editing

The real-time editing feature enables users to see each other's changes as they happen, providing immediate visual feedback and preventing conflicts.

Features include:

- **Live cursors**: See where other users are editing
- **Instant synchronization**: Changes appear immediately for all participants
- **Conflict prevention**: Smart conflict resolution when multiple users edit the same area
- **Asynchronous support**: Users can also work offline and sync changes later

### Invitation system

Collaborate with both internal users and external stakeholders through a flexible invitation system.

Invitation options:

- **Internal invitations**: Invite existing [[= product_name =]] users
- **External invitations**: Invite users outside your organization via email
- **Time-limited access**: Set expiration dates for collaboration sessions
- **Role-based permissions**: Control what invited users can do (view, edit, comment)

## Dashboard integration

Collaborative editing extends the back office dashboard with two new tabs:

- **My shared drafts**: Content you've shared with others
- **Drafts shared with me**: Content others have shared with you

These tabs provide quick access to active collaboration sessions and help users stay organized.

## Getting started

To start using collaborative editing:

1. [Install and configure](collaborative_editing_installation.md) the collaboration feature
2. [Set up user permissions](collaborative_editing_permissions.md) for collaboration
3. [Create your first collaboration session](collaborative_editing_usage.md)
4. Explore the [PHP API](collaborative_editing_api.md) for custom integrations

## Use cases

### Content review process

**Scenario**: Marketing team creates a blog post that needs review from legal and technical teams.

**Solution**: 
1. Marketing creates the initial draft
2. Share the draft with legal and technical reviewers
3. Reviewers can comment and suggest changes in real-time
4. Marketing incorporates feedback and publishes

### External contributor workflow

**Scenario**: Work with external consultants on product documentation.

**Solution**:
1. Create content structure internally
2. Invite external experts via email with time-limited access
3. Collaborate on content creation with real-time feedback
4. Review and publish internally after collaboration ends

### Multi-language content coordination

**Scenario**: Coordinate content creation across different language teams.

**Solution**:
1. Create master content in primary language
2. Share drafts with regional language teams
3. Parallel translation and localization work
4. Synchronized publication across all languages

## Technical overview

The collaborative editing system is built on several core components:

- **Session management**: Handles collaboration sessions and participant management
- **Real-time synchronization**: WebSocket-based real-time updates
- **Permission system**: Integration with [[= product_name =]] role and policy system
- **Notification system**: Email and in-app notifications for collaboration events
- **REST API**: Full API access for custom integrations

## Security considerations

Collaborative editing includes several security features:

- **Permission inheritance**: Respects existing content permissions
- **Audit logging**: All collaboration activities are logged
- **Session expiration**: Automatic cleanup of expired sessions
- **External user limitations**: Configurable restrictions for external collaborators

## Next steps

- [Installation and setup](collaborative_editing_installation.md)
- [User permissions configuration](collaborative_editing_permissions.md)
- [Using collaborative editing](collaborative_editing_usage.md)
- [PHP API reference](collaborative_editing_api.md)
- [Events and customization](collaborative_editing_events.md)