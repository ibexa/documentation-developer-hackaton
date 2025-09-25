---
description: Events that are triggered when working with collaboration.
page_type: reference
month_change: true
---

# Collaboration events

## Session management

The events below are dispatched when managing [collaboration sessions](../../content_management/collaborative_editing/collaborative_editing_guide.md):

| Event | Dispatched by |
|---|---|
|[BeforeCreateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeCreateSessionEvent.html)| Session Service |
|[CreateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-CreateSessionEvent.html)| Session Service |
|[BeforeUpdateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeUpdateSessionEvent.html)| Session Service |
|[UpdateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-UpdateSessionEvent.html)| Session Service |
|[BeforeDeleteSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeDeleteSessionEvent.html)| Session Service |
|[DeleteSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-DeleteSessionEvent.html)| Session Service |
|[JoinSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-JoinSessionEvent.html)| Session Service |
|[LeaveSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-LeaveSessionEvent.html)| Session Service |
|[SessionPublicPreviewEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-SessionPublicPreviewEvent.html)| Session Service |

## Invitation management

The events below are dispatched when managing collaboration invitations:

| Event | Dispatched by |
|---|---|
|[BeforeCreateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeCreateInvitationEvent.html)| Invitation Service |
|[CreateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-CreateInvitationEvent.html)| Invitation Service |
|[BeforeUpdateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeUpdateInvitationEvent.html)| Invitation Service |
|[UpdateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-UpdateInvitationEvent.html)| Invitation Service |
|[BeforeDeleteInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeDeleteInvitationEvent.html)| Invitation Service |
|[DeleteInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-DeleteInvitationEvent.html)| Invitation Service |

## Participant management

The events below are dispatched when managing collaboration participants:

| Event | Dispatched by |
|---|---|
|[BeforeAddParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeAddParticipantEvent.html)| Participant Service |
|[AddParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-AddParticipantEvent.html)| Participant Service |
|[BeforeUpdateParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeUpdateParticipantEvent.html)| Participant Service |
|[UpdateParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-UpdateParticipantEvent.html)| Participant Service |
|[BeforeRemoveParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeRemoveParticipantEvent.html)| Participant Service |
|[RemoveParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-RemoveParticipantEvent.html)| Participant Service |