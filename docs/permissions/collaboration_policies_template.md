---
description: Collaboration policies template based on PolicyProvider.php from ibexa/share
page_type: reference
---

# Collaboration Policies Template

This template documents the collaboration policies as defined in the PolicyProvider.php from ibexa/share repository.

The Collaboration bundle allows multiple people to work together on the same content. Teams can invite colleagues and external partners to join collaboration sessions where they can edit content in real-time or preview it before publication.

## Available policies

### Collaboration

The collaboration policies control various aspects of collaborative content editing, session management, invitations, and sharing.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`collaboration`</nobr> | <nobr>`create_session`</nobr> | Create session - Create new collaboration sessions for content items | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                              | <nobr>`view_session`</nobr> | View session - View collaboration sessions and their details | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`edit_session`</nobr> | Edit session - Modify collaboration session settings and permissions | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`delete_session`</nobr> | Delete session - Delete collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`join_session`</nobr> | Join session - Join existing collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                              | <nobr>`leave_session`</nobr> | Leave session - Leave collaboration sessions | None |
|                              | <nobr>`invite_user`</nobr> | Invite user - Create and send invitations to users for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`view_invitation`</nobr> | View invitation - View sent and received invitations | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`update_invitation`</nobr> | Update invitation - Modify invitation permissions (edit/preview access) | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`revoke_invitation`</nobr> | Revoke invitation - Revoke or delete invitations | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`accept_invitation`</nobr> | Accept invitation - Accept invitations to join collaboration sessions | None |
|                              | <nobr>`decline_invitation`</nobr> | Decline invitation - Decline invitations to join collaboration sessions | None |
|                              | <nobr>`preview_content`</nobr> | Preview content - Preview content in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation) |
|                              | <nobr>`edit_content`</nobr> | Edit content - Edit content collaboratively in real-time | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation)</br>[Owner](limitation_reference.md#owner-limitation)</br>[Location](limitation_reference.md#location-limitation)</br>[Subtree](limitation_reference.md#subtree-limitation)</br>[Field Group](limitation_reference.md#field-group-limitation) |
|                              | <nobr>`comment`</nobr> | Comment - Add comments and feedback in collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                              | <nobr>`create_link`</nobr> | Create link - Create shareable links for collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`view_link`</nobr> | View link - View and copy shareable links | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`manage_link`</nobr> | Manage link - Configure link settings (expiration, permissions) | [Owner](limitation_reference.md#owner-limitation) |
|                              | <nobr>`revoke_link`</nobr> | Revoke link - Revoke or disable shareable links | [Owner](limitation_reference.md#owner-limitation) |

## Policy Details

### Session Management Policies

- **collaboration/create_session**: Allows creating new collaboration sessions for content items
- **collaboration/view_session**: Allows viewing collaboration sessions and their details  
- **collaboration/edit_session**: Allows modifying collaboration session settings and permissions
- **collaboration/delete_session**: Allows deleting collaboration sessions
- **collaboration/join_session**: Allows joining existing collaboration sessions
- **collaboration/leave_session**: Allows leaving collaboration sessions

### Invitation Management Policies

- **collaboration/invite_user**: Allows creating and sending invitations to users for collaboration sessions
- **collaboration/view_invitation**: Allows viewing sent and received invitations
- **collaboration/update_invitation**: Allows modifying invitation permissions (edit/preview access)
- **collaboration/revoke_invitation**: Allows revoking or deleting invitations
- **collaboration/accept_invitation**: Allows accepting invitations to join collaboration sessions
- **collaboration/decline_invitation**: Allows declining invitations to join collaboration sessions

### Content Collaboration Policies

- **collaboration/preview_content**: Allows previewing content in collaboration sessions
- **collaboration/edit_content**: Allows editing content collaboratively in real-time
- **collaboration/comment**: Allows adding comments and feedback in collaboration sessions

### Sharing and Link Policies  

- **collaboration/create_link**: Allows creating shareable links for collaboration sessions
- **collaboration/view_link**: Allows viewing and copying shareable links
- **collaboration/manage_link**: Allows configuring link settings (expiration, permissions)
- **collaboration/revoke_link**: Allows revoking or disabling shareable links

## Related Documentation

- [Policies](policies.md) - Main policies reference
- [Custom policies](custom_policies.md) - How to create custom policies
- [Limitation reference](limitation_reference.md) - Available limitations
- [Permissions](permissions.md) - Overview of the permissions system