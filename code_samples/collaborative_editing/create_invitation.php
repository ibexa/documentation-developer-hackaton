<?php

declare(strict_types=1);

namespace App\Collaboration;

use Ibexa\Contracts\Collaboration\Invitation\InvitationServiceInterface;
use Ibexa\Contracts\Collaboration\Values\Invitation\InvitationCreateStruct;
use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\UserService;

class InvitationManager
{
    public function __construct(
        private InvitationServiceInterface $invitationService,
        private ContentService $contentService,
        private UserService $userService
    ) {
    }

    /**
     * Create a collaboration invitation for a content item
     */
    public function createInvitation(int $contentId, int $userId, string $message = ''): void
    {
        $content = $this->contentService->loadContent($contentId);
        $user = $this->userService->loadUser($userId);

        $invitationCreateStruct = new InvitationCreateStruct();
        $invitationCreateStruct->contentId = $contentId;
        $invitationCreateStruct->userId = $userId;
        $invitationCreateStruct->message = $message;
        $invitationCreateStruct->permissions = ['edit', 'comment'];
        
        $invitation = $this->invitationService->createInvitation($invitationCreateStruct);
        
        // Send notification to invited user
        // This would typically trigger email notifications
        echo "Invitation created for user {$user->getName()} to collaborate on '{$content->getName()}'";
    }

    /**
     * Create invitation for external user by email
     */
    public function createExternalInvitation(int $contentId, string $email, string $message = ''): void
    {
        $content = $this->contentService->loadContent($contentId);

        $invitationCreateStruct = new InvitationCreateStruct();
        $invitationCreateStruct->contentId = $contentId;
        $invitationCreateStruct->email = $email;
        $invitationCreateStruct->message = $message;
        $invitationCreateStruct->permissions = ['comment']; // Limited permissions for external users
        $invitationCreateStruct->expirationDate = new \DateTime('+7 days'); // Expires in 7 days
        
        $invitation = $this->invitationService->createInvitation($invitationCreateStruct);
        
        echo "External invitation created for {$email} to collaborate on '{$content->getName()}'";
    }
}