---
description: Events that are triggered when working with collaboration.
page_type: reference
month_change: true
---

# Collaboration events

## Session management

The events below are dispatched when managing [collaboration sessions](../../content_management/collaborative_editing/collaborative_editing_guide.md):

| Event | Dispatched by | Description |
|---|---|---|
|[BeforeCreateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeCreateSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched before a new collaboration session is created |
|[CreateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-CreateSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched after a collaboration session has been successfully created |
|[BeforeUpdateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeUpdateSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched before a collaboration session is updated |
|[UpdateSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-UpdateSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched after a collaboration session has been successfully updated |
|[BeforeDeleteSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-BeforeDeleteSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched before a collaboration session is deleted |
|[DeleteSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-DeleteSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched after a collaboration session has been successfully deleted |

### Session interactions

The following events are dispatched during user interactions with collaboration sessions:

| Event | Dispatched by | Description |
|---|---|---|
|[JoinSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-JoinSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched when a user joins a collaboration session |
|[LeaveSessionEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-LeaveSessionEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched when a user leaves a collaboration session |
|[SessionPublicPreviewEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Session-Event-SessionPublicPreviewEvent.html)| [SessionServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-SessionServiceInterface.html) | Dispatched when generating a public preview of a collaboration session |

## Invitation management

The events below are dispatched when managing collaboration invitations. These events allow you to customize the invitation workflow, add validation logic, or integrate with external notification systems:

| Event | Dispatched by | Description |
|---|---|---|
|[BeforeCreateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeCreateInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched before a new collaboration invitation is created |
|[CreateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-CreateInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched after a collaboration invitation has been successfully created |
|[BeforeUpdateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeUpdateInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched before a collaboration invitation is updated |
|[UpdateInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-UpdateInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched after a collaboration invitation has been successfully updated |
|[BeforeDeleteInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-BeforeDeleteInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched before a collaboration invitation is deleted |
|[DeleteInvitationEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Invitation-Event-DeleteInvitationEvent.html)| [InvitationServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-InvitationServiceInterface.html) | Dispatched after a collaboration invitation has been successfully deleted |

## Participant management

The events below are dispatched when managing collaboration participants. These events provide hooks for customizing participant permissions, tracking user activities, or implementing custom participant management logic:

| Event | Dispatched by | Description |
|---|---|---|
|[BeforeAddParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeAddParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched before a participant is added to a collaboration session |
|[AddParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-AddParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched after a participant has been successfully added to a collaboration session |
|[BeforeUpdateParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeUpdateParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched before a participant's information is updated |
|[UpdateParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-UpdateParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched after a participant's information has been successfully updated |
|[BeforeRemoveParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-BeforeRemoveParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched before a participant is removed from a collaboration session |
|[RemoveParticipantEvent](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-Participant-Event-RemoveParticipantEvent.html)| [ParticipantServiceInterface](/api/php_api/php_api_reference/classes/Ibexa-Contracts-Collaboration-ParticipantServiceInterface.html) | Dispatched after a participant has been successfully removed from a collaboration session |

## Event usage examples

### Subscribing to collaboration events

You can subscribe to these events to extend the collaboration functionality. For example, to send notifications when users join or leave sessions:

```php
use Ibexa\Contracts\Collaboration\Session\Event\JoinSessionEvent;
use Ibexa\Contracts\Collaboration\Session\Event\LeaveSessionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollaborationNotificationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            JoinSessionEvent::class => 'onUserJoinSession',
            LeaveSessionEvent::class => 'onUserLeaveSession',
        ];
    }

    public function onUserJoinSession(JoinSessionEvent $event): void
    {
        // Send notification when user joins a session
        $session = $event->getSession();
        $participant = $event->getParticipant();
        // Custom notification logic here
    }

    public function onUserLeaveSession(LeaveSessionEvent $event): void
    {
        // Send notification when user leaves a session
        $session = $event->getSession();
        $participant = $event->getParticipant();
        // Custom notification logic here
    }
}
```

### Customizing invitation workflow

Use invitation events to implement custom validation or approval workflows:

```php
use Ibexa\Contracts\Collaboration\Invitation\Event\BeforeCreateInvitationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InvitationWorkflowSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCreateInvitationEvent::class => 'validateInvitation',
        ];
    }

    public function validateInvitation(BeforeCreateInvitationEvent $event): void
    {
        $invitationCreateStruct = $event->getInvitationCreateStruct();
        
        // Implement custom validation logic
        if (!$this->isValidInvitation($invitationCreateStruct)) {
            // Stop the invitation creation
            $event->stopPropagation();
        }
    }
    
    private function isValidInvitation($invitationCreateStruct): bool
    {
        // Custom validation logic
        return true;
    }
}
```