---
description: Events related to collaborative editing features, including sessions, participants, and invitations.
---

# Collaboration events

Collaboration events are dispatched during various collaboration activities, allowing you to customize behavior and integrate with external systems.

## Session events

### Session lifecycle

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionCreateEvent` | Before creating a collaboration session |
| `Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent` | After a session is created |
| `Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionUpdateEvent` | Before updating session properties |
| `Ibexa\Contracts\Collaboration\Event\Session\SessionUpdatedEvent` | After session properties are updated |
| `Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionEndEvent` | Before ending a session |
| `Ibexa\Contracts\Collaboration\Event\Session\SessionEndedEvent` | After a session is ended |

### Session activity

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Session\SessionExpiredEvent` | When a session expires automatically |
| `Ibexa\Contracts\Collaboration\Event\Session\SessionSuspendedEvent` | When a session is suspended |
| `Ibexa\Contracts\Collaboration\Event\Session\SessionResumedEvent` | When a suspended session is resumed |

## Participant events

### Participant management

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Participant\BeforeParticipantAddEvent` | Before adding a participant |
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantAddedEvent` | After a participant is added |
| `Ibexa\Contracts\Collaboration\Event\Participant\BeforeParticipantRemoveEvent` | Before removing a participant |
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantRemovedEvent` | After a participant is removed |
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantUpdatedEvent` | When participant role or permissions change |

### Participant activity

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantJoinedEvent` | When a participant joins an active session |
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantLeftEvent` | When a participant leaves a session |
| `Ibexa\Contracts\Collaboration\Event\Participant\ParticipantActivityEvent` | When a participant performs an action |

## Invitation events

### Invitation lifecycle

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationCreatedEvent` | After an invitation is sent |
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationAcceptedEvent` | When an invitation is accepted |
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationDeclinedEvent` | When an invitation is declined |
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationExpiredEvent` | When an invitation expires |
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationCancelledEvent` | When an invitation is cancelled |

### Invitation reminders

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationReminderEvent` | When sending invitation reminders |
| `Ibexa\Contracts\Collaboration\Event\Invitation\InvitationFollowUpEvent` | For follow-up actions on invitations |

## Real-time editing events

### Content synchronization

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\RealTime\ContentChangedEvent` | When content is modified during collaboration |
| `Ibexa\Contracts\Collaboration\Event\RealTime\ConflictDetectedEvent` | When conflicting changes are detected |
| `Ibexa\Contracts\Collaboration\Event\RealTime\ConflictResolvedEvent` | After conflicts are resolved |
| `Ibexa\Contracts\Collaboration\Event\RealTime\SyncCompletedEvent` | After content synchronization |

### User presence

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\RealTime\ParticipantPresenceEvent` | When participant presence changes |
| `Ibexa\Contracts\Collaboration\Event\RealTime\CursorMovedEvent` | When a participant's cursor moves |
| `Ibexa\Contracts\Collaboration\Event\RealTime\UserConnectedEvent` | When a user connects to real-time session |
| `Ibexa\Contracts\Collaboration\Event\RealTime\UserDisconnectedEvent` | When a user disconnects from session |

## Notification events

### Email notifications

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Notification\EmailNotificationEvent` | Before sending email notifications |
| `Ibexa\Contracts\Collaboration\Event\Notification\EmailSentEvent` | After an email is sent |
| `Ibexa\Contracts\Collaboration\Event\Notification\EmailFailedEvent` | When email delivery fails |

### System notifications

| Event | Dispatched when |
|-------|----------------|
| `Ibexa\Contracts\Collaboration\Event\Notification\SystemNotificationEvent` | For in-app notifications |
| `Ibexa\Contracts\Collaboration\Event\Notification\NotificationReadEvent` | When a notification is read |
| `Ibexa\Contracts\Collaboration\Event\Notification\NotificationDismissedEvent` | When a notification is dismissed |

## Usage examples

### Basic event listener

```php
use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollaborationEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            SessionCreatedEvent::class => 'onSessionCreated',
        ];
    }

    public function onSessionCreated(SessionCreatedEvent $event): void
    {
        $session = $event->getSession();
        // Your custom logic here
    }
}
```

### Event listener with attributes

```php
use Ibexa\Contracts\Collaboration\Event\Participant\ParticipantAddedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class NotificationService
{
    #[AsEventListener(event: ParticipantAddedEvent::class)]
    public function onParticipantAdded(ParticipantAddedEvent $event): void
    {
        $participant = $event->getParticipant();
        $session = $event->getSession();
        
        // Send welcome notification
        $this->sendWelcomeNotification($participant, $session);
    }
}
```

### Preventable events

```php
use Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionEndEvent;

#[AsEventListener(event: BeforeSessionEndEvent::class)]
public function onBeforeSessionEnd(BeforeSessionEndEvent $event): void
{
    $session = $event->getSession();
    
    // Check if session can be ended
    if ($this->hasUnsavedChanges($session)) {
        // Prevent the session from ending
        $event->stopPropagation();
        
        // Optionally set a reason
        $event->setErrorMessage('Session has unsaved changes');
    }
}
```

## Event data access

### Session events

```php
public function onSessionCreated(SessionCreatedEvent $event): void
{
    $session = $event->getSession();               // CollaborationSession
    $sessionStruct = $event->getSessionCreateStruct(); // SessionCreateStruct (in BeforeSessionCreateEvent)
    
    // Access session data
    $sessionId = $session->id;
    $contentId = $session->contentId;
    $ownerId = $session->ownerId;
    $status = $session->status;
}
```

### Participant events

```php
public function onParticipantAdded(ParticipantAddedEvent $event): void
{
    $participant = $event->getParticipant();       // Participant
    $session = $event->getSession();               // CollaborationSession
    
    // Access participant data
    $userId = $participant->userId;
    $role = $participant->role;
    $permissions = $participant->permissions;
}
```

### Invitation events

```php
public function onInvitationCreated(InvitationCreatedEvent $event): void
{
    $invitation = $event->getInvitation();         // Invitation
    $session = $event->getSession();               // CollaborationSession
    
    // Access invitation data
    $email = $invitation->email;
    $role = $invitation->role;
    $token = $invitation->token;
    $expiresAt = $invitation->expiresAt;
}
```

## Integration with other systems

### Workflow integration

```php
use Ibexa\Contracts\Workflow\Event\Action\ActionExecutedEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;

#[AsEventListener(event: ActionExecutedEvent::class)]
public function onWorkflowAction(ActionExecutedEvent $event): void
{
    if ($event->getActionName() === 'collaboration_review') {
        // Create collaboration session from workflow
        $this->createCollaborationFromWorkflow($event->getWorkflowItem());
    }
}

#[AsEventListener(event: SessionCreatedEvent::class)]
public function onSessionCreated(SessionCreatedEvent $event): void
{
    $session = $event->getSession();
    
    // Update workflow if session was created from workflow
    if (isset($session->metadata['workflow_item_id'])) {
        $this->updateWorkflowProgress($session);
    }
}
```

### Search integration

```php
use Ibexa\Contracts\Collaboration\Event\Session\SessionEndedEvent;

#[AsEventListener(event: SessionEndedEvent::class)]
public function onSessionEnded(SessionEndedEvent $event): void
{
    $session = $event->getSession();
    
    // Update search index with collaboration metadata
    $this->updateContentSearchIndex($session);
}
```

## Custom events

You can create custom events for specific collaboration scenarios:

```php
use Ibexa\Contracts\Collaboration\Event\AbstractCollaborationEvent;

class CustomCollaborationEvent extends AbstractCollaborationEvent
{
    public function __construct(
        private CollaborationSession $session,
        private array $customData
    ) {
    }
    
    public function getSession(): CollaborationSession
    {
        return $this->session;
    }
    
    public function getCustomData(): array
    {
        return $this->customData;
    }
}

// Dispatch custom event
$this->eventDispatcher->dispatch(
    new CustomCollaborationEvent($session, $customData)
);
```

## Performance considerations

### Async event processing

For heavy operations, consider using message queues:

```php
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: SessionCreatedEvent::class)]
public function onSessionCreated(SessionCreatedEvent $event): void
{
    // Queue heavy operations
    $this->messageBus->dispatch(new ProcessSessionCreated($event->getSession()->id));
}
```

### Event batching

For high-frequency events, implement batching:

```php
class BatchedEventProcessor
{
    private array $eventQueue = [];
    
    #[AsEventListener(event: ParticipantActivityEvent::class)]
    public function queueParticipantActivity(ParticipantActivityEvent $event): void
    {
        $this->eventQueue[] = $event;
        
        if (count($this->eventQueue) >= 10) {
            $this->processBatch();
        }
    }
}
```

## See also

- [Collaborative editing guide](../../content_management/collaborative_editing/collaborative_editing_guide.md)
- [Collaborative editing API](../../content_management/collaborative_editing/collaborative_editing_api.md)
- [Event reference](event_reference.md)