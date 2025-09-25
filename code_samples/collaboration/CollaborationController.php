<?php

declare(strict_types=1);

namespace App\Controller;

use Ibexa\Contracts\Collaboration\CollaborationServiceInterface;
use Ibexa\Contracts\Collaboration\ParticipantServiceInterface;
use Ibexa\Contracts\Collaboration\InvitationServiceInterface;
use Ibexa\Contracts\Collaboration\Values\Session\SessionCreateStruct;
use Ibexa\Contracts\Collaboration\Values\Participant\ParticipantCreateStruct;
use Ibexa\Contracts\Collaboration\Values\Invitation\InvitationCreateStruct;
use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CollaborationController extends AbstractController
{
    public function __construct(
        private readonly CollaborationServiceInterface $collaborationService,
        private readonly ParticipantServiceInterface $participantService,
        private readonly InvitationServiceInterface $invitationService,
        private readonly ContentService $contentService,
        private readonly UserService $userService
    ) {
    }

    #[Route('/collaboration/session/create/{contentId}', name: 'collaboration_create_session', methods: ['POST'])]
    public function createSession(int $contentId, Request $request): JsonResponse
    {
        try {
            $content = $this->contentService->loadContent($contentId);
            
            $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            
            $sessionStruct = new SessionCreateStruct();
            $sessionStruct->contentId = $contentId;
            $sessionStruct->name = $requestData['name'] ?? 'Collaboration Session';
            
            if (isset($requestData['expires_at'])) {
                $sessionStruct->expiresAt = new \DateTime($requestData['expires_at']);
            }
            
            $session = $this->collaborationService->createSession($sessionStruct);
            
            return $this->json([
                'success' => true,
                'session' => [
                    'id' => $session->id,
                    'name' => $session->name,
                    'status' => $session->status,
                    'content_id' => $session->contentId,
                    'created_at' => $session->createdAt->format('c'),
                ],
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/collaboration/session/{sessionId}/invite', name: 'collaboration_invite_user', methods: ['POST'])]
    public function inviteUser(int $sessionId, Request $request): JsonResponse
    {
        try {
            $session = $this->collaborationService->getSession($sessionId);
            $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            
            $invitationStruct = new InvitationCreateStruct();
            $invitationStruct->sessionId = $sessionId;
            $invitationStruct->email = $requestData['email'];
            $invitationStruct->role = $requestData['role'] ?? 'editor';
            $invitationStruct->message = $requestData['message'] ?? '';
            
            if (isset($requestData['expires_at'])) {
                $invitationStruct->expiresAt = new \DateTime($requestData['expires_at']);
            }
            
            $invitation = $this->invitationService->createInvitation($invitationStruct);
            
            return $this->json([
                'success' => true,
                'invitation' => [
                    'id' => $invitation->id,
                    'email' => $invitation->email,
                    'role' => $invitation->role,
                    'status' => $invitation->status,
                    'created_at' => $invitation->createdAt->format('c'),
                ],
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/collaboration/session/{sessionId}/participants', name: 'collaboration_get_participants', methods: ['GET'])]
    public function getParticipants(int $sessionId): JsonResponse
    {
        try {
            $participants = $this->participantService->getParticipants($sessionId);
            
            $participantData = [];
            foreach ($participants as $participant) {
                $user = $this->userService->loadUser($participant->userId);
                
                $participantData[] = [
                    'id' => $participant->id,
                    'user_id' => $participant->userId,
                    'user_name' => $user->getName(),
                    'role' => $participant->role,
                    'permissions' => $participant->permissions,
                    'joined_at' => $participant->joinedAt->format('c'),
                    'last_activity' => $participant->lastActivity?->format('c'),
                ];
            }
            
            return $this->json([
                'success' => true,
                'participants' => $participantData,
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/collaboration/session/{sessionId}/join', name: 'collaboration_join_session', methods: ['POST'])]
    public function joinSession(int $sessionId): JsonResponse
    {
        try {
            $session = $this->collaborationService->getSession($sessionId);
            $currentUser = $this->userService->loadUser($this->getUser()->getUserIdentifier());
            
            $participantStruct = new ParticipantCreateStruct();
            $participantStruct->userId = $currentUser->id;
            $participantStruct->role = 'editor';
            $participantStruct->permissions = [
                'edit' => true,
                'comment' => true,
                'invite' => false,
            ];
            
            $participant = $this->participantService->addParticipant($session, $participantStruct);
            
            return $this->json([
                'success' => true,
                'participant' => [
                    'id' => $participant->id,
                    'role' => $participant->role,
                    'permissions' => $participant->permissions,
                    'joined_at' => $participant->joinedAt->format('c'),
                ],
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/collaboration/session/{sessionId}/end', name: 'collaboration_end_session', methods: ['POST'])]
    public function endSession(int $sessionId): JsonResponse
    {
        try {
            $this->collaborationService->endSession($sessionId);
            
            return $this->json([
                'success' => true,
                'message' => 'Session ended successfully',
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/collaboration/my-sessions', name: 'collaboration_my_sessions', methods: ['GET'])]
    public function getMySessions(): JsonResponse
    {
        try {
            $currentUser = $this->userService->loadUser($this->getUser()->getUserIdentifier());
            
            // Get sessions where user is owner
            $query = new \Ibexa\Contracts\Collaboration\Values\Session\Query\SessionQuery();
            $query->filter = new \Ibexa\Contracts\Collaboration\Values\Session\Query\Criterion\OwnerId($currentUser->id);
            $query->sortClauses = [
                new \Ibexa\Contracts\Collaboration\Values\Session\Query\SortClause\UpdatedAt(\Ibexa\Contracts\Core\Repository\Values\Content\Query::SORT_DESC),
            ];
            
            $ownedSessions = $this->collaborationService->findSessions($query);
            
            // Get sessions where user is participant
            $participantQuery = new \Ibexa\Contracts\Collaboration\Values\Session\Query\SessionQuery();
            $participantQuery->filter = new \Ibexa\Contracts\Collaboration\Values\Session\Query\Criterion\ParticipantId($currentUser->id);
            
            $participantSessions = $this->collaborationService->findSessions($participantQuery);
            
            return $this->json([
                'success' => true,
                'owned_sessions' => $this->formatSessionsData($ownedSessions->sessions),
                'participant_sessions' => $this->formatSessionsData($participantSessions->sessions),
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function formatSessionsData(array $sessions): array
    {
        $data = [];
        
        foreach ($sessions as $session) {
            try {
                $content = $this->contentService->loadContent($session->contentId);
                
                $data[] = [
                    'id' => $session->id,
                    'name' => $session->name,
                    'status' => $session->status,
                    'content' => [
                        'id' => $content->id,
                        'name' => $content->getName(),
                        'content_type' => $content->getContentType()->getName(),
                    ],
                    'created_at' => $session->createdAt->format('c'),
                    'expires_at' => $session->expiresAt?->format('c'),
                    'participant_count' => count($this->participantService->getParticipants($session->id)),
                ];
            } catch (\Exception $e) {
                // Skip sessions with inaccessible content
                continue;
            }
        }
        
        return $data;
    }
}