---
description: Policies for the Ibexa Collaboration bundle that manages collaborative content editing features.
page_type: reference
---

# Collaboration Policies

The Collaboration bundle allows multiple people to work together on the same content. Teams can invite colleagues and external partners to join collaboration sessions where they can edit content in real-time or just preview it before publication.

This document describes the permission policies you need to set up collaboration features like creating sessions, inviting users, and managing who can edit or preview content.

## Available policies

### Collaboration

#### Collaboration sessions

The collaboration session policies control which actions can be performed on collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration`</nobr> | <nobr>`create_session`</nobr> | create new collaboration sessions for content items | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                              | <nobr>`view_session`</nobr> | view collaboration sessions and their details | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`edit_session`</nobr> | modify collaboration session settings and permissions | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`delete_session`</nobr> | delete collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`join_session`</nobr> | join existing collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                              | <nobr>`leave_session`</nobr> | leave collaboration sessions |  |

#### Collaboration invitations

The collaboration invitation policies control which actions can be performed on invitations to collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration`</nobr> | <nobr>`invite_user`</nobr> | create and send invitations to users for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`view_invitation`</nobr> | view sent and received invitations | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`update_invitation`</nobr> | modify invitation permissions (edit/preview access) | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`revoke_invitation`</nobr> | revoke or delete invitations | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`accept_invitation`</nobr> | accept invitations to join collaboration sessions |  |
|                              | <nobr>`decline_invitation`</nobr> | decline invitations to join collaboration sessions |  |

#### Collaboration content

The collaboration content policies control collaborative editing and preview functionality.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration`</nobr> | <nobr>`preview_content`</nobr> | preview content in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                              | <nobr>`edit_content`</nobr> | edit content collaboratively in real-time | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation)</br>[Field Group](limitation_reference.md#field-group-limitation) |
|                              | <nobr>`comment`</nobr> | add comments and feedback in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |

#### Collaboration sharing

The collaboration sharing policies control shareable links that allow external access to collaboration sessions.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration`</nobr> | <nobr>`create_link`</nobr> | create shareable links for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`view_link`</nobr> | view and copy shareable links | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`manage_link`</nobr> | configure link settings (expiration, permissions) | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`revoke_link`</nobr> | revoke or disable shareable links | [Owner](limitation_reference.md#owner-limitation) |

## Related Documentation

- [Collaborative editing product guide](../content_management/collaborative_editing/collaborative_editing_guide.md) - Overview of collaboration features
- [Policies](policies.md) - Main policies reference
- [Custom policies](custom_policies.md) - How to create custom policies
- [Limitation reference](limitation_reference.md) - Available limitations
- [Permissions](permissions.md) - Overview of the permissions system