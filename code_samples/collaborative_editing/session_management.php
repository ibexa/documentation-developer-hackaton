<?php

declare(strict_types=1);

namespace App\Collaboration;

use Ibexa\Contracts\Collaboration\Session\SessionServiceInterface;
use Ibexa\Contracts\Collaboration\Values\Session\SessionCreateStruct;
use Ibexa\Contracts\Collaboration\Values\Session\SessionQuery;
use Ibexa\Contracts\Collaboration\Session\Query\Criterion;
use Ibexa\Contracts\Collaboration\Session\Query\SortClause;

class CollaborationSessionManager
{
    public function __construct(
        private SessionServiceInterface $sessionService
    ) {
    }

    /**
     * Create a new collaboration session
     */
    public function createSession(int $contentId, array $participants = []): void
    {
        $sessionCreateStruct = new SessionCreateStruct();
        $sessionCreateStruct->contentId = $contentId;
        $sessionCreateStruct->participants = $participants;
        $sessionCreateStruct->settings = [
            'realTimeEnabled' => true,
            'allowExternalUsers' => false,
            'maxParticipants' => 10,
            'autoSave' => true,
        ];
        
        $session = $this->sessionService->createSession($sessionCreateStruct);
        
        echo "Collaboration session created with ID: {$session->getId()}";
    }

    /**
     * Find active collaboration sessions for a user
     */
    public function findUserSessions(int $userId): array
    {
        $query = new SessionQuery();
        $query->filter = new Criterion\ParticipantId($userId);
        $query->sortClauses = [
            new SortClause\UpdatedAt('DESC'),
        ];
        $query->limit = 50;

        $searchResult = $this->sessionService->findSessions($query);

        return $searchResult->sessions;
    }

    /**
     * Join an existing collaboration session
     */
    public function joinSession(int $sessionId, int $userId): void
    {
        $session = $this->sessionService->loadSession($sessionId);
        
        // Add user as participant
        $this->sessionService->addParticipant($sessionId, $userId);
        
        echo "User {$userId} joined collaboration session {$sessionId}";
    }

    /**
     * Leave a collaboration session
     */
    public function leaveSession(int $sessionId, int $userId): void
    {
        $this->sessionService->removeParticipant($sessionId, $userId);
        
        echo "User {$userId} left collaboration session {$sessionId}";
    }

    /**
     * Get session statistics
     */
    public function getSessionStats(int $sessionId): array
    {
        $session = $this->sessionService->loadSession($sessionId);
        $participants = $this->sessionService->getSessionParticipants($sessionId);
        
        return [
            'session_id' => $session->getId(),
            'content_id' => $session->getContentId(),
            'created_at' => $session->getCreatedAt(),
            'updated_at' => $session->getUpdatedAt(),
            'participant_count' => count($participants),
            'active_participants' => array_filter($participants, fn($p) => $p->isActive()),
            'real_time_enabled' => $session->getSetting('realTimeEnabled', false),
        ];
    }
}