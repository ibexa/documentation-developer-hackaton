<?php

declare(strict_types=1);

namespace App\EventListener;

use Ibexa\Contracts\Collaboration\Session\Event\JoinSessionEvent;
use Ibexa\Contracts\Collaboration\Session\Event\SessionPublicPreviewEvent;
use Ibexa\Contracts\Core\Repository\ContentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollaborationEventListener implements EventSubscriberInterface
{
    public function __construct(
        private ContentService $contentService,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JoinSessionEvent::class => 'onJoinSession',
            SessionPublicPreviewEvent::class => 'onSessionPublicPreview',
        ];
    }

    /**
     * Handle user joining a collaboration session
     */
    public function onJoinSession(JoinSessionEvent $event): void
    {
        $session = $event->getSession();
        $user = $event->getUser();
        
        $this->logger->info('User joined collaboration session', [
            'user_id' => $user->getId(),
            'user_name' => $user->getName(),
            'session_id' => $session->getId(),
            'content_id' => $session->getContentId(),
        ]);

        // Custom logic: Send notification to other participants
        $this->notifyOtherParticipants($session, $user);
        
        // Custom logic: Update user activity metrics
        $this->updateUserActivityMetrics($user->getId(), 'session_join');
    }

    /**
     * Handle public preview access for collaboration session
     */
    public function onSessionPublicPreview(SessionPublicPreviewEvent $event): void
    {
        $session = $event->getSession();
        $content = $this->contentService->loadContent($session->getContentId());
        
        $this->logger->info('Public preview accessed for collaboration session', [
            'session_id' => $session->getId(),
            'content_id' => $session->getContentId(),
            'content_name' => $content->getName(),
            'preview_url' => $event->getPreviewUrl(),
        ]);

        // Custom logic: Track public preview usage
        $this->trackPublicPreviewAccess($session, $event->getPreviewUrl());
    }

    /**
     * Notify other participants when someone joins the session
     */
    private function notifyOtherParticipants($session, $user): void
    {
        // Implementation would send real-time notifications
        // to other active participants in the session
        $this->logger->debug('Notifying other participants of new joiner', [
            'session_id' => $session->getId(),
            'new_participant' => $user->getName(),
        ]);
    }

    /**
     * Update user activity metrics for analytics
     */
    private function updateUserActivityMetrics(int $userId, string $action): void
    {
        // Implementation would update user activity statistics
        // for collaboration analytics and reporting
        $this->logger->debug('Updated user activity metrics', [
            'user_id' => $userId,
            'action' => $action,
            'timestamp' => new \DateTime(),
        ]);
    }

    /**
     * Track public preview access for analytics
     */
    private function trackPublicPreviewAccess($session, string $previewUrl): void
    {
        // Implementation would track public preview usage
        // for collaboration session analytics
        $this->logger->debug('Tracked public preview access', [
            'session_id' => $session->getId(),
            'preview_url' => $previewUrl,
            'access_time' => new \DateTime(),
        ]);
    }
}