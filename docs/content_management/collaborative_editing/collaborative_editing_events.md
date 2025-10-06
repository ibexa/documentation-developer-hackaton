---
description: Learn about collaboration events and how to customize collaborative editing behavior.
---

# Collaborative editing events

The collaborative editing system dispatches various events that allow you to customize behavior, add custom logic, and integrate with external systems.

## Session events

### Session lifecycle events

#### BeforeSessionCreateEvent

Dispatched before creating a collaboration session.

```php
use Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionCreateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeSessionCreateEvent::class => ['onBeforeSessionCreate', 10],
        ];
    }
    
    public function onBeforeSessionCreate(BeforeSessionCreateEvent $event): void
    {
        $struct = $event->getSessionCreateStruct();
        
        // Auto-generate session name if not provided
        if (empty($struct->name)) {
            $content = $this->contentService->loadContent($struct->contentId);
            $struct->name = "Collaboration on: " . $content->getName();
        }
        
        // Set default expiration
        if (!$struct->expiresAt) {
            $struct->expiresAt = new \DateTime('+7 days');
        }
        
        // Add metadata
        $struct->metadata['creator_ip'] = $this->request->getClientIp();
        $struct->metadata['created_via'] = 'web_interface';
    }
}
```

#### SessionCreatedEvent

Dispatched after a session is successfully created.

```php
use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;

public function onSessionCreated(SessionCreatedEvent $event): void
{
    $session = $event->getSession();
    
    // Send notification to content stakeholders
    $content = $this->contentService->loadContent($session->contentId);
    $stakeholders = $this->getContentStakeholders($content);
    
    foreach ($stakeholders as $stakeholder) {
        $this->notificationService->send($stakeholder, 'session_created', [
            'session' => $session,
            'content' => $content,
        ]);
    }
    
    // Log session creation for audit
    $this->logger->info('Collaboration session created', [
        'session_id' => $session->id,
        'content_id' => $session->contentId,
        'owner_id' => $session->ownerId,
    ]);
}
```

#### BeforeSessionUpdateEvent / SessionUpdatedEvent

```php
use Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionUpdateEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionUpdatedEvent;

public function onBeforeSessionUpdate(BeforeSessionUpdateEvent $event): void
{
    $session = $event->getSession();
    $struct = $event->getSessionUpdateStruct();
    
    // Validate session updates
    if ($struct->expiresAt && $struct->expiresAt < new \DateTime()) {
        throw new \InvalidArgumentException('Cannot set expiration date in the past');
    }
    
    // Track what's being changed
    $changes = [];
    if (isset($struct->name) && $struct->name !== $session->name) {
        $changes['name'] = ['old' => $session->name, 'new' => $struct->name];
    }
    
    $event->setMetadata('changes', $changes);
}

public function onSessionUpdated(SessionUpdatedEvent $event): void
{
    $session = $event->getSession();
    $changes = $event->getMetadata('changes', []);
    
    if (!empty($changes)) {
        // Notify participants of changes
        $this->notifyParticipantsOfUpdate($session, $changes);
        
        // Log the update
        $this->auditLogger->info('Session updated', [
            'session_id' => $session->id,
            'changes' => $changes,
        ]);
    }
}
```

#### BeforeSessionEndEvent / SessionEndedEvent

```php
use Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionEndEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionEndedEvent;

public function onBeforeSessionEnd(BeforeSessionEndEvent $event): void
{
    $session = $event->getSession();
    
    // Archive session data before ending
    $this->archiveService->archiveSession($session);
    
    // Check for unsaved changes
    $unsavedChanges = $this->checkForUnsavedChanges($session);
    if ($unsavedChanges) {
        // Optionally prevent session ending
        // $event->stopPropagation();
        
        // Or warn the user
        $event->setMetadata('unsaved_changes', $unsavedChanges);
    }
}

public function onSessionEnded(SessionEndedEvent $event): void
{
    $session = $event->getSession();
    
    // Notify all participants
    $participants = $this->participantService->getParticipants($session->id);
    foreach ($participants as $participant) {
        $this->notificationService->send($participant->userId, 'session_ended', [
            'session' => $session,
        ]);
    }
    
    // Generate session report
    $report = $this->reportService->generateSessionReport($session);
    $this->emailService->sendSessionReport($session->ownerId, $report);
}
```

## Participant events

### ParticipantAddedEvent

Dispatched when a user joins a collaboration session.

```php
use Ibexa\Contracts\Collaboration\Event\Participant\ParticipantAddedEvent;

public function onParticipantAdded(ParticipantAddedEvent $event): void
{
    $participant = $event->getParticipant();
    $session = $event->getSession();
    
    // Welcome new participant
    $this->notificationService->send($participant->userId, 'welcome_to_session', [
        'session' => $session,
        'role' => $participant->role,
    ]);
    
    // Notify other participants
    $otherParticipants = $this->participantService->getOtherParticipants(
        $session->id,
        $participant->userId
    );
    
    foreach ($otherParticipants as $other) {
        $this->notificationService->send($other->userId, 'participant_joined', [
            'session' => $session,
            'new_participant' => $participant,
        ]);
    }
    
    // Initialize real-time presence if enabled
    if ($this->isRealTimeEnabled($session)) {
        $this->realTimeService->addParticipantPresence($participant);
    }
}
```

### ParticipantRemovedEvent

```php
use Ibexa\Contracts\Collaboration\Event\Participant\ParticipantRemovedEvent;

public function onParticipantRemoved(ParticipantRemovedEvent $event): void
{
    $participant = $event->getParticipant();
    $session = $event->getSession();
    
    // Clean up participant data
    $this->cleanupParticipantData($participant);
    
    // Remove from real-time presence
    if ($this->isRealTimeEnabled($session)) {
        $this->realTimeService->removeParticipantPresence($participant);
    }
    
    // Notify remaining participants
    $remainingParticipants = $this->participantService->getParticipants($session->id);
    foreach ($remainingParticipants as $other) {
        $this->notificationService->send($other->userId, 'participant_left', [
            'session' => $session,
            'removed_participant' => $participant,
        ]);
    }
}
```

## Invitation events

### InvitationCreatedEvent

```php
use Ibexa\Contracts\Collaboration\Event\Invitation\InvitationCreatedEvent;

public function onInvitationCreated(InvitationCreatedEvent $event): void
{
    $invitation = $event->getInvitation();
    $session = $this->collaborationService->getSession($invitation->sessionId);
    
    // Send invitation email
    $this->emailService->sendInvitationEmail($invitation, [
        'session' => $session,
        'inviter' => $this->userService->loadUser($session->ownerId),
        'invitation_url' => $this->generateInvitationUrl($invitation),
    ]);
    
    // Track invitation metrics
    $this->metricsService->incrementCounter('collaboration.invitations.sent', [
        'session_id' => $session->id,
        'role' => $invitation->role,
        'user_type' => $invitation->userId ? 'internal' : 'external',
    ]);
}
```

### InvitationAcceptedEvent / InvitationDeclinedEvent

```php
use Ibexa\Contracts\Collaboration\Event\Invitation\InvitationAcceptedEvent;
use Ibexa\Contracts\Collaboration\Event\Invitation\InvitationDeclinedEvent;

public function onInvitationAccepted(InvitationAcceptedEvent $event): void
{
    $invitation = $event->getInvitation();
    $participant = $event->getParticipant();
    $session = $this->collaborationService->getSession($invitation->sessionId);
    
    // Track acceptance
    $this->metricsService->incrementCounter('collaboration.invitations.accepted');
    
    // Send welcome message
    $this->notificationService->send($participant->userId, 'invitation_accepted', [
        'session' => $session,
        'participant' => $participant,
    ]);
    
    // Update session activity
    $this->updateSessionActivity($session);
}

public function onInvitationDeclined(InvitationDeclinedEvent $event): void
{
    $invitation = $event->getInvitation();
    $session = $this->collaborationService->getSession($invitation->sessionId);
    
    // Track declination
    $this->metricsService->incrementCounter('collaboration.invitations.declined');
    
    // Notify session owner
    $this->notificationService->send($session->ownerId, 'invitation_declined', [
        'invitation' => $invitation,
        'session' => $session,
    ]);
}
```

## Real-time editing events

### ContentChangedEvent

```php
use Ibexa\Contracts\Collaboration\Event\RealTime\ContentChangedEvent;

public function onContentChanged(ContentChangedEvent $event): void
{
    $change = $event->getChange();
    $session = $event->getSession();
    $userId = $event->getUserId();
    
    // Broadcast change to other participants via WebSocket
    $this->webSocketService->broadcast($session->id, [
        'type' => 'content_change',
        'user_id' => $userId,
        'change' => $change,
        'timestamp' => time(),
    ], [$userId]); // Exclude the user who made the change
    
    // Store change history
    $this->changeHistoryService->recordChange($session, $change, $userId);
    
    // Check for conflicts
    if ($this->hasConflict($change, $session)) {
        $this->conflictResolutionService->handleConflict($session, $change);
    }
}
```

### ParticipantPresenceEvent

```php
use Ibexa\Contracts\Collaboration\Event\RealTime\ParticipantPresenceEvent;

public function onParticipantPresence(ParticipantPresenceEvent $event): void
{
    $presence = $event->getPresence();
    $session = $event->getSession();
    
    // Update participant last activity
    $this->participantService->updateLastActivity($presence->participantId);
    
    // Broadcast presence to other participants
    $this->webSocketService->broadcast($session->id, [
        'type' => 'participant_presence',
        'participant_id' => $presence->participantId,
        'cursor_position' => $presence->cursorPosition,
        'is_active' => $presence->isActive,
    ], [$presence->participantId]);
}
```

## Content workflow integration events

### WorkflowActionExecutedEvent integration

```php
use Ibexa\Contracts\Workflow\Event\Action\ActionExecutedEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;

public function onWorkflowActionExecuted(ActionExecutedEvent $event): void
{
    if ($event->getActionName() === 'request_collaboration_review') {
        $workflowItem = $event->getWorkflowItem();
        $content = $this->contentService->loadContent($workflowItem->getSubject()->id);
        
        // Create collaboration session for workflow review
        $sessionStruct = new SessionCreateStruct();
        $sessionStruct->contentId = $content->id;
        $sessionStruct->name = "Workflow Review: " . $content->getName();
        $sessionStruct->metadata = [
            'workflow_item_id' => $workflowItem->getId(),
            'workflow_name' => $workflowItem->getWorkflowName(),
            'created_from' => 'workflow_action',
        ];
        
        $session = $this->collaborationService->createSession($sessionStruct);
        
        // Auto-invite reviewers based on workflow metadata
        $reviewers = $workflowItem->getMetadata('reviewers', []);
        foreach ($reviewers as $reviewerId) {
            $this->inviteUserToSession($session, $reviewerId, 'reviewer');
        }
    }
}
```

## Custom event examples

### SessionActivityEvent

Create custom events for specific use cases:

```php
use Ibexa\Contracts\Collaboration\Event\AbstractCollaborationEvent;

class SessionActivityEvent extends AbstractCollaborationEvent
{
    public function __construct(
        private CollaborationSession $session,
        private string $activityType,
        private array $activityData = []
    ) {}
    
    public function getSession(): CollaborationSession
    {
        return $this->session;
    }
    
    public function getActivityType(): string
    {
        return $this->activityType;
    }
    
    public function getActivityData(): array
    {
        return $this->activityData;
    }
}

// Usage
$this->eventDispatcher->dispatch(
    new SessionActivityEvent($session, 'content_updated', [
        'field' => 'title',
        'old_value' => 'Old Title',
        'new_value' => 'New Title',
    ])
);
```

### NotificationEvent integration

```php
use Ibexa\Contracts\Notifications\Event\NotificationEvent;

public function onSessionActivity(SessionActivityEvent $event): void
{
    $session = $event->getSession();
    $activity = $event->getActivityType();
    
    // Create notification for session activity
    $notificationEvent = new NotificationEvent(
        'collaboration_activity',
        $session->ownerId,
        [
            'session_id' => $session->id,
            'activity_type' => $activity,
            'activity_data' => $event->getActivityData(),
        ]
    );
    
    $this->eventDispatcher->dispatch($notificationEvent);
}
```

## Event listener registration

### Via service configuration

```yaml
# config/services.yaml
services:
    App\EventSubscriber\CollaborationEventSubscriber:
        tags:
            - name: kernel.event_subscriber
    
    App\EventListener\SessionNotificationListener:
        tags:
            - name: kernel.event_listener
              event: Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent
              method: onSessionCreated
              priority: 100
```

### Via PHP attributes

```php
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CollaborationNotificationService
{
    #[AsEventListener(event: SessionCreatedEvent::class, priority: 100)]
    public function onSessionCreated(SessionCreatedEvent $event): void
    {
        // Handle session created
    }
    
    #[AsEventListener(event: ParticipantAddedEvent::class)]
    public function onParticipantAdded(ParticipantAddedEvent $event): void
    {
        // Handle participant added
    }
}
```

## Event debugging and monitoring

### Debug event listeners

```bash
# List all collaboration event listeners
php bin/console debug:event-dispatcher | grep -i collaboration

# Debug specific event
php bin/console debug:event-dispatcher SessionCreatedEvent
```

### Event monitoring

```php
use Psr\Log\LoggerInterface;

class CollaborationEventMonitor implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}
    
    public static function getSubscribedEvents(): array
    {
        return [
            // Monitor all collaboration events with low priority
            SessionCreatedEvent::class => ['logEvent', -100],
            ParticipantAddedEvent::class => ['logEvent', -100],
            InvitationCreatedEvent::class => ['logEvent', -100],
        ];
    }
    
    public function logEvent($event): void
    {
        $eventName = get_class($event);
        $data = $this->extractEventData($event);
        
        $this->logger->info("Collaboration event: {$eventName}", $data);
    }
    
    private function extractEventData($event): array
    {
        $data = [];
        
        if (method_exists($event, 'getSession')) {
            $data['session_id'] = $event->getSession()->id;
        }
        
        if (method_exists($event, 'getParticipant')) {
            $data['participant_id'] = $event->getParticipant()->id;
        }
        
        return $data;
    }
}
```

## Performance considerations

### Async event processing

```php
use Symfony\Component\Messenger\MessageBusInterface;

class AsyncCollaborationEventHandler
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {}
    
    public function onSessionCreated(SessionCreatedEvent $event): void
    {
        // Process heavy operations asynchronously
        $this->messageBus->dispatch(new SessionCreatedMessage(
            $event->getSession()->id
        ));
    }
}

// Message handler
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SessionCreatedMessageHandler
{
    public function __invoke(SessionCreatedMessage $message): void
    {
        $session = $this->collaborationService->getSession($message->sessionId);
        
        // Perform heavy operations
        $this->generateSessionAnalytics($session);
        $this->updateSearchIndex($session);
        $this->syncWithExternalSystem($session);
    }
}
```

### Event batching

```php
class BatchCollaborationEventProcessor
{
    private array $eventQueue = [];
    
    public function queueEvent($event): void
    {
        $this->eventQueue[] = $event;
        
        if (count($this->eventQueue) >= 10) {
            $this->processBatch();
        }
    }
    
    private function processBatch(): void
    {
        // Process events in batches for better performance
        $notifications = [];
        
        foreach ($this->eventQueue as $event) {
            if ($event instanceof SessionCreatedEvent) {
                $notifications[] = $this->createSessionNotification($event);
            }
        }
        
        // Send all notifications at once
        $this->notificationService->sendBatch($notifications);
        
        $this->eventQueue = [];
    }
}
```

## Best practices

### Event handling best practices

- **Keep listeners lightweight**: Avoid heavy operations in event listeners
- **Use appropriate priority**: Set listener priority based on importance
- **Handle exceptions**: Prevent one listener from breaking others
- **Use async processing**: Move heavy operations to message queues
- **Log important events**: Maintain audit trail for collaboration activities

### Security considerations

- **Validate event data**: Don't trust event data without validation
- **Check permissions**: Verify user permissions in event listeners  
- **Sanitize input**: Clean any user-provided data in events
- **Rate limiting**: Implement rate limiting for event-triggered actions
- **Audit logging**: Log security-relevant collaboration events

## Next steps

- [Explore the PHP API](collaborative_editing_api.md)
- [View event reference documentation](../../api/event_reference/collaboration_events.md)