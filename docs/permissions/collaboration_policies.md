---
description: Policies for the Ibexa Collaboration bundle that manages collaborative content editing features.
page_type: reference
---

# Collaboration Policies

The Collaboration bundle allows multiple people to work together on the same content. Teams can invite colleagues and external partners to join collaboration sessions where they can edit content in real-time or just preview it before publication.

This document describes the permission policies you need to set up collaboration features like creating sessions, inviting users, and managing who can edit or preview content.

## Available policies

### Collaboration Sessions

#### Collaboration Session Management

Policies for managing collaboration sessions that enable multiple users to work on content simultaneously.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration_session`</nobr> | <nobr>`create`</nobr> | create new collaboration sessions for content items | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                                      | <nobr>`view`</nobr> | view collaboration sessions and their details | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                                      | <nobr>`edit`</nobr> | modify collaboration session settings and permissions | [Owner](limitation_reference.md#owner-limitation) |
|                                      | <nobr>`delete`</nobr> | delete collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                                      | <nobr>`join`</nobr> | join existing collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                                      | <nobr>`leave`</nobr> | leave collaboration sessions |  |

### Collaboration Invitations

#### Invitation Management

Policies for managing invitations sent to users to join collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration_invitation`</nobr> | <nobr>`create`</nobr> | create and send invitations to users for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                                         | <nobr>`view`</nobr> | view sent and received invitations | [Owner](limitation_reference.md#owner-limitation) |
|                                         | <nobr>`update`</nobr> | modify invitation permissions (edit/preview access) | [Owner](limitation_reference.md#owner-limitation) |
|                                         | <nobr>`delete`</nobr> | revoke or delete invitations | [Owner](limitation_reference.md#owner-limitation) |
|                                         | <nobr>`accept`</nobr> | accept invitations to join collaboration sessions |  |
|                                         | <nobr>`decline`</nobr> | decline invitations to join collaboration sessions |  |

### Collaboration Participants

#### Participant Management

Policies for managing participants in collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration_participant`</nobr> | <nobr>`view`</nobr> | view participants in collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                                          | <nobr>`manage`</nobr> | add or remove participants from collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                                          | <nobr>`change_permissions`</nobr> | modify participant permissions (edit/preview) | [Owner](limitation_reference.md#owner-limitation) |

### Collaboration Content

#### Content Collaboration

Policies for collaborative content editing and preview functionality.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration_content`</nobr> | <nobr>`preview`</nobr> | preview content in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                                      | <nobr>`edit`</nobr> | edit content collaboratively in real-time | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation)</br>[Field Group](limitation_reference.md#field-group-limitation) |
|                                      | <nobr>`comment`</nobr> | add comments and feedback in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |

### Collaboration Sharing

#### Shareable Links

Policies for managing shareable links that allow external access to collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration_share`</nobr> | <nobr>`create_link`</nobr> | create shareable links for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                                   | <nobr>`view_link`</nobr> | view and copy shareable links | [Owner](limitation_reference.md#owner-limitation) |
|                                   | <nobr>`manage_link`</nobr> | configure link settings (expiration, permissions) | [Owner](limitation_reference.md#owner-limitation) |
|                                   | <nobr>`revoke_link`</nobr> | revoke or disable shareable links | [Owner](limitation_reference.md#owner-limitation) |

## Combining collaboration policies

Collaboration policies work together to provide comprehensive access control:

- **Session creation** requires both `collaboration_session/create` and appropriate `content` policies
- **Inviting users** requires `collaboration_invitation/create` and `collaboration_participant/manage`
- **Real-time editing** requires both `collaboration_content/edit` and standard `content/edit` policies
- **External sharing** requires `collaboration_share/create_link` and session management permissions

## Usage Examples

### Content Editor Role
For users who can create and manage collaboration sessions:
- `collaboration_session/*` - Full session management
- `collaboration_invitation/*` - Full invitation management  
- `collaboration_participant/manage` - Manage participants
- `collaboration_share/*` - Manage shareable links

### Collaborator Role
For users who can participate in collaboration sessions:
- `collaboration_session/join` - Join sessions
- `collaboration_session/leave` - Leave sessions
- `collaboration_invitation/accept` - Accept invitations
- `collaboration_content/preview` - Preview content
- `collaboration_content/edit` - Edit content (if editing permissions granted)

### Reviewer Role
For users who can only review content:
- `collaboration_session/join` - Join sessions
- `collaboration_content/preview` - Preview content
- `collaboration_content/comment` - Add feedback

## Related Documentation

- [Collaborative editing product guide](../content_management/collaborative_editing/collaborative_editing_guide.md) - Overview of collaboration features
- [Policies](policies.md) - Main policies reference
- [Custom policies](custom_policies.md) - How to create custom policies
- [Limitation reference](limitation_reference.md) - Available limitations
- [Permissions](permissions.md) - Overview of the permissions system