---
description: Learn about Ibexa DXP's Collaborative Editing feature that enables multiple users to work together on content creation and editing.
page_type: landing_page
month_change: true
editions: [headless, experience, commerce]
---

# Collaborative editing product guide

**Collaborative editing** is a powerful feature in Ibexa DXP that allows multiple users to work together on content creation and editing in real-time or asynchronously. This feature enhances teamwork, streamlines the review process, and improves overall content management efficiency.

## Capabilities and benefits

### Real-time collaboration

With collaborative editing, multiple users can:

- **Preview, review, and edit** the same content simultaneously
- **See changes in real-time** as other users make modifications
- **Work asynchronously** on content when real-time collaboration isn't needed
- **Invite internal and external users** to collaboration sessions

### Enhanced workflow management

The collaborative editing feature provides:

- **Multiple sharing options** for inviting collaborators
- **Dashboard integration** with dedicated tabs for managing shared content:
  - **My shared drafts** - Content you've shared with others
  - **Drafts shared with me** - Content others have shared with you
- **Organized collaboration** helping users stay on top of their collaborative work

### User management and security

Collaborative editing supports:

- **Internal user collaboration** - Work with team members within your organization
- **External user sharing** - Invite external contributors and reviewers
- **Secure invitation system** - Control who can access and edit your content
- **Permission-based access** - Maintain control over editing capabilities

## How it works

### Starting a collaboration session

1. **Open content** you want to collaborate on in the Ibexa DXP back office
2. **Use the sharing options** to invite collaborators
3. **Send invitations** to internal or external users
4. **Begin collaborative editing** once users join the session

### Managing shared content

Use the dedicated dashboard tabs to:

- **Monitor active collaborations** in "My shared drafts"
- **Access content shared with you** in "Drafts shared with me"
- **Track collaboration status** and participant activity
- **Manage permissions** and session settings

### Real-time editing features

When multiple users are editing simultaneously:

- **Live cursor tracking** shows where other users are working
- **Real-time content updates** display changes as they happen
- **Conflict resolution** helps manage concurrent edits
- **Version management** maintains content integrity

## Getting started

### Prerequisites

Collaborative editing is available in:

- [[= product_name_headless =]]
- [[= product_name_exp =]]
- [[= product_name_com =]]

### Installation and configuration

Collaborative editing is part of the core Ibexa DXP functionality. To enable the feature:

1. **Ensure you have the collaboration package** installed:
   ```bash
   composer require ibexa/collaboration
   ```

2. **Configure user permissions** for collaborative editing
3. **Set up notification preferences** for collaboration invitations
4. **Configure sharing options** based on your organization's needs

### User interface

The collaborative editing interface includes:

- **Collaboration controls** in the content editing interface
- **User presence indicators** showing active collaborators
- **Real-time change notifications** and visual cues
- **Dashboard tabs** for managing collaborative content

## Technical implementation

### PHP API

Collaborative editing provides extensive PHP API coverage through the `Ibexa\Contracts\Collaboration` namespace, including:

- **Invitation management** - Create and manage collaboration invitations
- **Session handling** - Control collaboration sessions and participants
- **Participant management** - Add, remove, and manage session participants
- **Configuration options** - Customize collaborative editing behavior

#### Code examples

The following code examples demonstrate key collaborative editing functionality:

- **[Creating invitations](../../code_samples/collaborative_editing/create_invitation.php)** - Shows how to invite internal and external users to collaborate
- **[Session management](../../code_samples/collaborative_editing/session_management.php)** - Demonstrates session creation, joining, and management
- **[Event handling](../../code_samples/collaborative_editing/collaboration_event_listener.php)** - Example event listener for collaboration events

For detailed API documentation, see:
- [Collaboration PHP API](https://doc.ibexa.co/en/5.0/api/php_api/php_api_reference/namespaces/ibexa-contracts-collaboration.html)
- [Share PHP API](https://doc.ibexa.co/en/5.0/api/php_api/php_api_reference/namespaces/ibexa-contracts-share.html)

### REST API

The collaborative editing feature includes REST API endpoints for:

- **Invitation** - Manage collaboration invitations via API
- **CollaborationSession** - Control session lifecycle and settings
- **Participant** - Handle participant management
- **ParticipantList** - Retrieve and manage participant lists

### Events and integration

The feature provides comprehensive event coverage for custom integrations:

- **JoinSessionEvent** - Triggered when users join collaboration sessions
- **SessionPublicPreviewEvent** - Handles public preview access
- **Invitation events** - Manage invitation lifecycle events
- **Participant events** - Track participant activity and changes

For detailed information on available events and how to use them, see [Collaborative editing events](collaborative_editing_events.md).

### Search and queries

Collaborative editing includes advanced query capabilities:

- **Sort clauses** for organizing collaboration data:
  - CreatedAt, UpdatedAt sorting for invitations and sessions
  - Status-based sorting for invitation management
  - ID-based sorting for consistent ordering

- **Criteria system** for filtering collaborative content
- **Real-time search** integration for finding shared content

## Use cases

### Content team collaboration

- **Editorial teams** working together on articles and blog posts
- **Marketing teams** collaborating on campaign content
- **Product teams** developing documentation and specifications

### Review and approval workflows

- **Content review processes** with multiple stakeholders
- **Approval workflows** involving managers and subject matter experts
- **Quality assurance** processes with dedicated reviewers

### External collaboration

- **Client collaboration** on project content and deliverables
- **Freelancer integration** for content creation and editing
- **Partner collaboration** on joint content initiatives

### Educational and training content

- **Course development** with multiple instructors
- **Training material creation** with subject matter experts
- **Collaborative knowledge base** development

## Best practices

### Organizing collaborative content

- **Use clear naming conventions** for shared drafts
- **Set appropriate permissions** for different types of collaborators
- **Regularly review** shared content in dashboard tabs
- **Establish clear workflows** for collaborative editing processes

### Managing real-time collaboration

- **Communicate changes** effectively during real-time sessions
- **Avoid conflicting edits** by coordinating with other users
- **Use version control** features to track significant changes
- **Save work frequently** to prevent data loss

### Security and access control

- **Review external user access** regularly
- **Use time-limited sharing** when appropriate
- **Monitor collaboration activity** through dashboard tools
- **Implement proper user permission policies**

## Integration with other features

### Workflow integration

Collaborative editing works seamlessly with:

- **Content workflow systems** for approval processes
- **Version management** for tracking collaborative changes
- **Notification systems** for collaboration alerts

### Dashboard integration

The feature integrates with:

- **User dashboards** with dedicated collaboration tabs
- **Activity feeds** showing collaboration updates
- **Task management** for collaborative content projects

### Search and content discovery

Collaborative editing enhances:

- **Content search** with collaboration-aware filtering
- **Content organization** through shared content categorization
- **Content recommendations** based on collaboration patterns

## Troubleshooting

### Common issues

- **Permission problems** - Check user roles and collaboration permissions
- **Real-time sync issues** - Verify network connectivity and browser settings
- **Invitation delivery** - Check email settings and spam filters
- **Performance optimization** - Monitor concurrent user limits and system resources

### Support resources

For additional help with collaborative editing:

- Check the [PHP API documentation](https://doc.ibexa.co/en/5.0/api/php_api/php_api_reference/namespaces/ibexa-contracts-collaboration.html)
- Review [event reference documentation](../../api/event_reference/)
- Consult the [user guide](https://doc.ibexa.co/projects/userguide/en/master/) for end-user instructions
- Contact Ibexa support for advanced configuration assistance

---

*Collaborative editing is a game-changing feature that transforms how teams work together on content. By enabling real-time collaboration and providing comprehensive management tools, it streamlines content creation processes and improves team productivity.*