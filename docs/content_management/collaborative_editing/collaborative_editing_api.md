---
description: Use the collaborative editing PHP API to integrate collaboration features into your applications.
---

# Collaborative editing PHP API

The collaborative editing feature provides a comprehensive PHP API for managing collaboration sessions, participants, invitations, and real-time synchronization.

## Core services

### CollaborationService

The main service for managing collaboration sessions.

```php
use Ibexa\Contracts\Collaboration\CollaborationServiceInterface;

class MyCollaborationController
{
    public function __construct(
        private CollaborationServiceInterface $collaborationService
    ) {}
    
    public function createSession(Content $content): CollaborationSession
    {
        $sessionCreateStruct = new SessionCreateStruct();
        $sessionCreateStruct->contentId = $content->id;
        $sessionCreateStruct->name = 'Review Session';
        $sessionCreateStruct->expiresAt = new \DateTime('+1 day');
        
        return $this->collaborationService->createSession($sessionCreateStruct);
    }
}
```

#### Key methods

| Method | Description |
|--------|-------------|
| `createSession(SessionCreateStruct $struct)` | Create a new collaboration session |
| `getSession(int $sessionId)` | Retrieve session by ID |
| `updateSession(CollaborationSession $session, SessionUpdateStruct $struct)` | Update session properties |
| `endSession(int $sessionId)` | End an active session |
| `findSessions(SessionQuery $query)` | Search for sessions |

### ParticipantService

Manages session participants and their permissions.

```php
use Ibexa\Contracts\Collaboration\ParticipantServiceInterface;

public function addParticipant(
    CollaborationSession $session,
    User $user,
    string $role = 'editor'
): Participant {
    $participantStruct = new ParticipantCreateStruct();
    $participantStruct->userId = $user->id;
    $participantStruct->role = $role;
    $participantStruct->permissions = [
        'edit' => true,
        'comment' => true,
        'invite' => false,
    ];
    
    return $this->participantService->addParticipant($session, $participantStruct);
}
```

#### Participant management methods

| Method | Description |
|--------|-------------|
| `addParticipant(CollaborationSession $session, ParticipantCreateStruct $struct)` | Add user to session |
| `removeParticipant(int $sessionId, int $userId)` | Remove participant |
| `updateParticipant(Participant $participant, ParticipantUpdateStruct $struct)` | Update participant role/permissions |
| `getParticipants(int $sessionId)` | List session participants |
| `isParticipant(int $sessionId, int $userId)` | Check if user is participant |

### InvitationService

Handles invitation creation and management.

```php
use Ibexa\Contracts\Collaboration\InvitationServiceInterface;

public function inviteUser(CollaborationSession $session, string $email): Invitation
{
    $invitationStruct = new InvitationCreateStruct();
    $invitationStruct->sessionId = $session->id;
    $invitationStruct->email = $email;
    $invitationStruct->role = 'reviewer';
    $invitationStruct->message = 'Please review this content';
    $invitationStruct->expiresAt = new \DateTime('+7 days');
    
    return $this->invitationService->createInvitation($invitationStruct);
}
```

#### Invitation methods

| Method | Description |
|--------|-------------|
| `createInvitation(InvitationCreateStruct $struct)` | Send invitation |
| `acceptInvitation(string $token)` | Accept invitation by token |
| `declineInvitation(string $token)` | Decline invitation |
| `cancelInvitation(int $invitationId)` | Cancel pending invitation |
| `findInvitations(InvitationQuery $query)` | Search invitations |

## Value objects

### CollaborationSession

Represents a collaboration session.

```php
use Ibexa\Contracts\Collaboration\Values\Session\CollaborationSession;

class CollaborationSession extends ValueObject
{
    public readonly int $id;
    public readonly int $contentId;
    public readonly int $ownerId;
    public readonly string $name;
    public readonly string $status;
    public readonly \DateTimeInterface $createdAt;
    public readonly ?\DateTimeInterface $expiresAt;
    public readonly array $metadata;
}
```

#### Session status values

- `active` - Session is currently active
- `expired` - Session has expired
- `ended` - Session was manually ended
- `suspended` - Session is temporarily suspended

### Participant

Represents a session participant.

```php
use Ibexa\Contracts\Collaboration\Values\Participant\Participant;

class Participant extends ValueObject
{
    public readonly int $id;
    public readonly int $sessionId;
    public readonly int $userId;
    public readonly string $role;
    public readonly array $permissions;
    public readonly \DateTimeInterface $joinedAt;
    public readonly ?\DateTimeInterface $lastActivity;
}
```

#### Participant roles

- `owner` - Session owner with full control
- `admin` - Can manage session and participants
- `editor` - Can edit content and comment
- `reviewer` - Can review, comment, and approve
- `viewer` - Read-only access with commenting

### Invitation

Represents a collaboration invitation.

```php
use Ibexa\Contracts\Collaboration\Values\Invitation\Invitation;

class Invitation extends ValueObject
{
    public readonly int $id;
    public readonly int $sessionId;
    public readonly string $email;
    public readonly ?int $userId;
    public readonly string $token;
    public readonly string $status;
    public readonly string $role;
    public readonly \DateTimeInterface $createdAt;
    public readonly ?\DateTimeInterface $expiresAt;
}
```

## Query and search

### SessionQuery

Search for collaboration sessions.

```php
use Ibexa\Contracts\Collaboration\Values\Session\Query\SessionQuery;
use Ibexa\Contracts\Collaboration\Values\Session\Query\Criterion;
use Ibexa\Contracts\Collaboration\Values\Session\Query\SortClause;

$query = new SessionQuery();
$query->filter = new Criterion\LogicalAnd([
    new Criterion\Status(['active']),
    new Criterion\OwnerId($currentUserId),
    new Criterion\ContentType(['article', 'blog_post']),
]);

$query->sortClauses = [
    new SortClause\UpdatedAt(Query::SORT_DESC),
];

$query->limit = 20;
$query->offset = 0;

$sessions = $this->collaborationService->findSessions($query);
```

#### Available criteria

| Criterion | Description |
|-----------|-------------|
| `Status` | Filter by session status |
| `OwnerId` | Filter by session owner |
| `ParticipantId` | Sessions where user is participant |
| `ContentId` | Filter by content ID |
| `ContentType` | Filter by content type |
| `DateRange` | Filter by date range |

#### Sort clauses

| Sort Clause | Description |
|-------------|-------------|
| `CreatedAt` | Sort by creation date |
| `UpdatedAt` | Sort by last update |
| `ExpiresAt` | Sort by expiration date |
| `Name` | Sort alphabetically by name |

### InvitationQuery

Search for invitations.

```php
use Ibexa\Contracts\Collaboration\Values\Invitation\Query\InvitationQuery;
use Ibexa\Contracts\Collaboration\Values\Invitation\Query\Criterion as InvitationCriterion;

$query = new InvitationQuery();
$query->filter = new InvitationCriterion\LogicalAnd([
    new InvitationCriterion\Status(['pending']),
    new InvitationCriterion\Email($userEmail),
]);

$invitations = $this->invitationService->findInvitations($query);
```

## Real-time editing API

### RealTimeEditingService

Manages real-time collaboration features.

```php
use Ibexa\Contracts\Collaboration\RealTimeEditingServiceInterface;

public function enableRealTimeEditing(CollaborationSession $session): void
{
    $this->realTimeService->enableRealTime($session->id, [
        'sync_interval' => 1000, // milliseconds
        'conflict_resolution' => 'last_write_wins',
        'cursor_tracking' => true,
    ]);
}

public function syncChanges(int $sessionId, array $changes): array
{
    return $this->realTimeService->syncChanges($sessionId, $changes);
}
```

### WebSocket integration

```php
use Ibexa\Contracts\Collaboration\WebSocket\CollaborationWebSocketInterface;

public function broadcastChange(int $sessionId, array $change): void
{
    $this->webSocketService->broadcast($sessionId, [
        'type' => 'content_change',
        'user_id' => $this->currentUser->id,
        'change' => $change,
        'timestamp' => time(),
    ]);
}
```

## Events and listeners

### Collaboration events

The system dispatches various events during collaboration:

```php
use Ibexa\Contracts\Collaboration\Event\Session\BeforeSessionCreateEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollaborationEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeSessionCreateEvent::class => 'onBeforeSessionCreate',
            SessionCreatedEvent::class => 'onSessionCreated',
        ];
    }
    
    public function onBeforeSessionCreate(BeforeSessionCreateEvent $event): void
    {
        $struct = $event->getSessionCreateStruct();
        
        // Add custom validation
        if (empty($struct->name)) {
            $struct->name = 'Collaboration Session ' . date('Y-m-d H:i');
        }
    }
    
    public function onSessionCreated(SessionCreatedEvent $event): void
    {
        $session = $event->getSession();
        
        // Send notification to content owner
        $this->notificationService->notify(
            $session->ownerId,
            'collaboration_session_created',
            ['session' => $session]
        );
    }
}
```

#### Available events

**Session events**:
- `BeforeSessionCreateEvent` / `SessionCreatedEvent`
- `BeforeSessionUpdateEvent` / `SessionUpdatedEvent`
- `BeforeSessionEndEvent` / `SessionEndedEvent`

**Participant events**:
- `BeforeParticipantAddEvent` / `ParticipantAddedEvent`
- `BeforeParticipantRemoveEvent` / `ParticipantRemovedEvent`
- `ParticipantUpdatedEvent`

**Invitation events**:
- `InvitationCreatedEvent`
- `InvitationAcceptedEvent` / `InvitationDeclinedEvent`
- `InvitationExpiredEvent`

## Custom integrations

### Custom session types

Create custom session types for specific workflows:

```php
use Ibexa\Contracts\Collaboration\Values\Session\SessionCreateStruct;

class ReviewSessionCreateStruct extends SessionCreateStruct
{
    public array $reviewers = [];
    public ?\DateTime $reviewDeadline = null;
    public bool $requireAllApprovals = false;
}

class ReviewCollaborationService
{
    public function createReviewSession(
        Content $content,
        array $reviewers,
        \DateTime $deadline
    ): CollaborationSession {
        $struct = new ReviewSessionCreateStruct();
        $struct->contentId = $content->id;
        $struct->reviewers = $reviewers;
        $struct->reviewDeadline = $deadline;
        $struct->requireAllApprovals = true;
        
        $session = $this->collaborationService->createSession($struct);
        
        // Auto-invite reviewers
        foreach ($reviewers as $reviewerId) {
            $this->addReviewer($session, $reviewerId);
        }
        
        return $session;
    }
}
```

### Integration with workflow

Connect collaboration with content workflows:

```php
use Ibexa\Contracts\Workflow\Event\Action\ActionExecutedEvent;

class WorkflowCollaborationIntegration
{
    public function onWorkflowAction(ActionExecutedEvent $event): void
    {
        $workflowItem = $event->getWorkflowItem();
        
        if ($event->getActionName() === 'collaboration_review') {
            $content = $this->contentService->loadContent(
                $workflowItem->getSubject()->id
            );
            
            // Create collaboration session for review stage
            $session = $this->createCollaborationFromWorkflow($content, $workflowItem);
            
            // Auto-assign reviewers based on workflow metadata
            $this->assignWorkflowReviewers($session, $workflowItem);
        }
    }
}
```

### REST API extension

Extend the REST API for collaboration features:

```php
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

class CollaborationSessionValueObjectVisitor extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('CollaborationSession');
        
        $generator->startValueElement('id', $data->id);
        $generator->endValueElement('id');
        
        $generator->startValueElement('contentId', $data->contentId);
        $generator->endValueElement('contentId');
        
        // Add participants
        $generator->startObjectElement('participants');
        foreach ($data->participants as $participant) {
            $visitor->visitValueObject($participant);
        }
        $generator->endObjectElement('participants');
        
        $generator->endObjectElement('CollaborationSession');
    }
}
```

## Performance optimization

### Caching strategies

```php
use Symfony\Contracts\Cache\CacheInterface;

class CachedCollaborationService
{
    public function __construct(
        private CollaborationServiceInterface $collaborationService,
        private CacheInterface $cache
    ) {}
    
    public function getSession(int $sessionId): CollaborationSession
    {
        return $this->cache->get(
            "collaboration_session_{$sessionId}",
            function () use ($sessionId) {
                return $this->collaborationService->getSession($sessionId);
            }
        );
    }
    
    public function invalidateSessionCache(int $sessionId): void
    {
        $this->cache->delete("collaboration_session_{$sessionId}");
    }
}
```

### Batch operations

```php
public function createBulkInvitations(
    CollaborationSession $session,
    array $emails,
    string $role = 'reviewer'
): array {
    $invitations = [];
    
    // Process in batches to avoid memory issues
    $batches = array_chunk($emails, 50);
    
    foreach ($batches as $batch) {
        $batchInvitations = $this->invitationService->createBulkInvitations(
            $session->id,
            $batch,
            $role
        );
        
        $invitations = array_merge($invitations, $batchInvitations);
    }
    
    return $invitations;
}
```

## Error handling

### Custom exceptions

```php
use Ibexa\Contracts\Collaboration\Exception\CollaborationException;

class SessionExpiredException extends CollaborationException
{
    public function __construct(CollaborationSession $session)
    {
        parent::__construct(
            "Collaboration session {$session->id} has expired"
        );
    }
}

class InsufficientPermissionsException extends CollaborationException
{
    public function __construct(string $permission, int $userId)
    {
        parent::__construct(
            "User {$userId} does not have '{$permission}' permission"
        );
    }
}
```

### Exception handling in controllers

```php
public function joinSessionAction(int $sessionId): Response
{
    try {
        $session = $this->collaborationService->getSession($sessionId);
        
        if (!$session->isActive()) {
            throw new SessionExpiredException($session);
        }
        
        $participant = $this->participantService->addCurrentUserToSession($session);
        
        return $this->json(['success' => true, 'participant' => $participant]);
        
    } catch (SessionExpiredException $e) {
        return $this->json(['error' => 'Session has expired'], 410);
    } catch (InsufficientPermissionsException $e) {
        return $this->json(['error' => 'Access denied'], 403);
    } catch (CollaborationException $e) {
        return $this->json(['error' => $e->getMessage()], 400);
    }
}
```

## Testing collaboration features

### Unit testing

```php
use PHPUnit\Framework\TestCase;
use Ibexa\Contracts\Collaboration\CollaborationServiceInterface;

class CollaborationServiceTest extends TestCase
{
    public function testCreateSession(): void
    {
        $content = $this->createMockContent();
        
        $struct = new SessionCreateStruct();
        $struct->contentId = $content->id;
        $struct->name = 'Test Session';
        
        $session = $this->collaborationService->createSession($struct);
        
        $this->assertEquals($content->id, $session->contentId);
        $this->assertEquals('Test Session', $session->name);
        $this->assertEquals('active', $session->status);
    }
}
```

### Integration testing

```php
use Ibexa\Tests\Integration\Core\Repository\BaseTest;

class CollaborationIntegrationTest extends BaseTest
{
    public function testFullCollaborationWorkflow(): void
    {
        // Create content
        $content = $this->createTestContent();
        
        // Create session
        $session = $this->createTestSession($content);
        
        // Invite participant
        $invitation = $this->inviteTestUser($session);
        
        // Accept invitation
        $participant = $this->acceptInvitation($invitation);
        
        // Test collaboration
        $this->assertTrue($session->isParticipant($participant->userId));
        $this->assertEquals('editor', $participant->role);
    }
}
```

## Next steps

- [Explore collaboration events](collaborative_editing_events.md)
- [View PHP API reference](../../api/php_api/php_api_reference/namespaces/ibexa-contracts-collaboration.html)