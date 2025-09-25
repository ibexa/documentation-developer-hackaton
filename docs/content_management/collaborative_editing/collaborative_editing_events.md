---
description: Learn about events triggered during collaborative editing sessions in Ibexa DXP.
---

# Collaborative editing events

The collaborative editing feature in Ibexa DXP provides a comprehensive event system that allows developers to hook into various collaboration lifecycle stages and customize the behavior according to their needs.

## Available events

### Session events

#### JoinSessionEvent

The `JoinSessionEvent` is triggered when a user joins a collaboration session.

**Event class:** `Ibexa\Contracts\Collaboration\Session\Event\JoinSessionEvent`

**Properties:**
- `getSession()` - Returns the collaboration session
- `getUser()` - Returns the user joining the session
- `getTimestamp()` - Returns when the join occurred

**Use cases:**
- Send notifications to other participants
- Log user activity for analytics
- Apply custom permissions or restrictions
- Initialize user-specific collaboration settings

#### SessionPublicPreviewEvent

The `SessionPublicPreviewEvent` is triggered when public preview access is requested for a collaboration session.

**Event class:** `Ibexa\Contracts\Collaboration\Session\Event\SessionPublicPreviewEvent`

**Properties:**
- `getSession()` - Returns the collaboration session
- `getPreviewUrl()` - Returns the public preview URL
- `getAccessTime()` - Returns when preview was accessed

**Use cases:**
- Track public preview usage
- Apply access restrictions
- Log preview activity
- Generate analytics reports

### Invitation events

Invitation events are triggered during the invitation lifecycle management.

**Available invitation events:**
- `BeforeCreateInvitationEvent` - Before creating an invitation
- `AfterCreateInvitationEvent` - After invitation is created
- `BeforeAcceptInvitationEvent` - Before invitation is accepted
- `AfterAcceptInvitationEvent` - After invitation is accepted
- `BeforeDeclineInvitationEvent` - Before invitation is declined
- `AfterDeclineInvitationEvent` - After invitation is declined

### Participant events

Participant events handle user participation in collaboration sessions.

**Available participant events:**
- `BeforeAddParticipantEvent` - Before adding a participant to session
- `AfterAddParticipantEvent` - After participant is added
- `BeforeRemoveParticipantEvent` - Before removing a participant
- `AfterRemoveParticipantEvent` - After participant is removed

## Event listener examples

### Basic event listener

```php
<?php

namespace App\EventListener;

use Ibexa\Contracts\Collaboration\Session\Event\JoinSessionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollaborationEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            JoinSessionEvent::class => 'onJoinSession',
        ];
    }

    public function onJoinSession(JoinSessionEvent $event): void
    {
        $session = $event->getSession();
        $user = $event->getUser();
        
        // Custom logic here
        error_log("User {$user->getName()} joined session {$session->getId()}");
    }
}
```

### Advanced event handling

For more complex event handling scenarios, see the [collaboration event listener example](../../code_samples/collaborative_editing/collaboration_event_listener.php).

## Event configuration

### Registering event listeners

Register your event listeners in your bundle's service configuration:

```yaml
# config/services.yaml
services:
    App\EventListener\CollaborationEventListener:
        tags:
            - { name: kernel.event_subscriber }
```

### Event priorities

You can set event listener priorities to control execution order:

```yaml
services:
    App\EventListener\CollaborationEventListener:
        tags:
            - { name: kernel.event_listener, event: Ibexa\Contracts\Collaboration\Session\Event\JoinSessionEvent, priority: 100 }
```

## Best practices

### Performance considerations

- **Keep event listeners lightweight** - Avoid heavy processing in event listeners
- **Use queued processing** for time-consuming tasks triggered by events  
- **Cache frequently accessed data** to avoid repeated database queries
- **Log appropriately** - Balance debugging needs with performance

### Error handling

- **Handle exceptions gracefully** in event listeners to avoid breaking the collaboration flow
- **Log errors appropriately** for debugging and monitoring
- **Provide fallback behavior** when possible
- **Test event listeners thoroughly** including error scenarios

### Security considerations

- **Validate user permissions** in event listeners
- **Sanitize user input** from event data
- **Audit sensitive operations** triggered by events
- **Implement rate limiting** for resource-intensive event handlers

## Integration patterns

### Notification systems

Use collaboration events to integrate with notification systems:

```php
public function onJoinSession(JoinSessionEvent $event): void
{
    $this->notificationService->sendRealTimeNotification(
        $event->getSession()->getParticipants(),
        'user_joined_session',
        ['user' => $event->getUser()->getName()]
    );
}
```

### Analytics and reporting

Collect collaboration metrics using events:

```php
public function onJoinSession(JoinSessionEvent $event): void
{
    $this->analyticsService->trackEvent('collaboration_session_join', [
        'session_id' => $event->getSession()->getId(),
        'user_id' => $event->getUser()->getId(),
        'timestamp' => $event->getTimestamp(),
    ]);
}
```

### Workflow integration

Integrate with content workflows:

```php
public function onSessionComplete(SessionCompleteEvent $event): void
{
    $content = $this->contentService->loadContent($event->getSession()->getContentId());
    
    // Trigger workflow transition
    $this->workflowService->apply($content, 'review_completed');
}
```

## Troubleshooting

### Common issues

- **Event not triggered** - Check event listener registration and service configuration
- **Performance degradation** - Profile event listeners and optimize heavy operations
- **Circular dependencies** - Avoid triggering events from within event listeners that could cause loops
- **Permission errors** - Ensure proper user context and permissions in event handlers

### Debugging events

Enable detailed logging for collaboration events:

```yaml
# config/packages/dev/monolog.yaml
monolog:
    handlers:
        collaboration:
            type: stream
            path: '%kernel.logs_dir%/collaboration.log'
            level: debug
            channels: ['collaboration']
```

Then use the collaboration channel in your event listeners:

```php
$this->logger->debug('Collaboration event triggered', [
    'event' => get_class($event),
    'session_id' => $event->getSession()->getId(),
]);
```