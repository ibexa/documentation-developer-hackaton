<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Ibexa\Contracts\Collaboration\Event\Session\SessionCreatedEvent;
use Ibexa\Contracts\Collaboration\Event\Session\SessionEndedEvent;
use Ibexa\Contracts\Collaboration\Event\Participant\ParticipantAddedEvent;
use Ibexa\Contracts\Collaboration\Event\Participant\ParticipantRemovedEvent;
use Ibexa\Contracts\Collaboration\Event\Invitation\InvitationCreatedEvent;
use Ibexa\Contracts\Collaboration\Event\Invitation\InvitationAcceptedEvent;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Core\Repository\ContentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class CollaborationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ContentService $contentService,
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly LoggerInterface $logger,
        private readonly string $fromEmail = 'noreply@example.com'
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SessionCreatedEvent::class => ['onSessionCreated', 10],
            SessionEndedEvent::class => ['onSessionEnded', 10],
            ParticipantAddedEvent::class => ['onParticipantAdded', 10],
            ParticipantRemovedEvent::class => ['onParticipantRemoved', 10],
            InvitationCreatedEvent::class => ['onInvitationCreated', 10],
            InvitationAcceptedEvent::class => ['onInvitationAccepted', 10],
        ];
    }

    public function onSessionCreated(SessionCreatedEvent $event): void
    {
        $session = $event->getSession();
        
        try {
            $content = $this->contentService->loadContent($session->contentId);
            $owner = $this->userService->loadUser($session->ownerId);
            
            $this->logger->info('Collaboration session created', [
                'session_id' => $session->id,
                'content_id' => $session->contentId,
                'content_name' => $content->getName(),
                'owner_id' => $session->ownerId,
                'owner_name' => $owner->getName(),
            ]);
            
            // Send notification email to owner
            $this->sendSessionCreatedEmail($session, $owner, $content);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling session created event', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function onSessionEnded(SessionEndedEvent $event): void
    {
        $session = $event->getSession();
        
        try {
            $this->logger->info('Collaboration session ended', [
                'session_id' => $session->id,
                'content_id' => $session->contentId,
                'duration' => $session->createdAt->diff(new \DateTime())->format('%d days %h hours'),
            ]);
            
            // Clean up session data if needed
            $this->cleanupSessionData($session);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling session ended event', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function onParticipantAdded(ParticipantAddedEvent $event): void
    {
        $participant = $event->getParticipant();
        $session = $event->getSession();
        
        try {
            $user = $this->userService->loadUser($participant->userId);
            $content = $this->contentService->loadContent($session->contentId);
            
            $this->logger->info('Participant added to collaboration session', [
                'session_id' => $session->id,
                'participant_id' => $participant->id,
                'user_id' => $participant->userId,
                'user_name' => $user->getName(),
                'role' => $participant->role,
            ]);
            
            // Send welcome email to new participant
            $this->sendParticipantWelcomeEmail($participant, $session, $user, $content);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling participant added event', [
                'session_id' => $session->id,
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function onParticipantRemoved(ParticipantRemovedEvent $event): void
    {
        $participant = $event->getParticipant();
        $session = $event->getSession();
        
        try {
            $this->logger->info('Participant removed from collaboration session', [
                'session_id' => $session->id,
                'participant_id' => $participant->id,
                'user_id' => $participant->userId,
                'role' => $participant->role,
            ]);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling participant removed event', [
                'session_id' => $session->id,
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function onInvitationCreated(InvitationCreatedEvent $event): void
    {
        $invitation = $event->getInvitation();
        
        try {
            $session = $event->getSession();
            $content = $this->contentService->loadContent($session->contentId);
            $owner = $this->userService->loadUser($session->ownerId);
            
            $this->logger->info('Collaboration invitation created', [
                'invitation_id' => $invitation->id,
                'session_id' => $invitation->sessionId,
                'email' => $invitation->email,
                'role' => $invitation->role,
            ]);
            
            // Send invitation email
            $this->sendInvitationEmail($invitation, $session, $owner, $content);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling invitation created event', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function onInvitationAccepted(InvitationAcceptedEvent $event): void
    {
        $invitation = $event->getInvitation();
        $participant = $event->getParticipant();
        
        try {
            $session = $event->getSession();
            $user = $this->userService->loadUser($participant->userId);
            
            $this->logger->info('Collaboration invitation accepted', [
                'invitation_id' => $invitation->id,
                'session_id' => $invitation->sessionId,
                'participant_id' => $participant->id,
                'user_name' => $user->getName(),
            ]);
            
            // Notify session owner about accepted invitation
            $this->sendInvitationAcceptedEmail($invitation, $session, $user);
            
        } catch (\Exception $e) {
            $this->logger->error('Error handling invitation accepted event', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendSessionCreatedEmail($session, $owner, $content): void
    {
        try {
            $email = (new Email())
                ->from($this->fromEmail)
                ->to($owner->email)
                ->subject('Collaboration Session Created')
                ->html($this->twig->render('emails/collaboration/session_created.html.twig', [
                    'session' => $session,
                    'owner' => $owner,
                    'content' => $content,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send session created email', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendParticipantWelcomeEmail($participant, $session, $user, $content): void
    {
        try {
            $email = (new Email())
                ->from($this->fromEmail)
                ->to($user->email)
                ->subject('Welcome to Collaboration Session')
                ->html($this->twig->render('emails/collaboration/participant_welcome.html.twig', [
                    'participant' => $participant,
                    'session' => $session,
                    'user' => $user,
                    'content' => $content,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send participant welcome email', [
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendInvitationEmail($invitation, $session, $owner, $content): void
    {
        try {
            $invitationUrl = sprintf(
                'https://example.com/collaboration/invitation/accept/%s',
                $invitation->token
            );

            $email = (new Email())
                ->from($this->fromEmail)
                ->to($invitation->email)
                ->subject(sprintf('Invitation to Collaborate on "%s"', $content->getName()))
                ->html($this->twig->render('emails/collaboration/invitation.html.twig', [
                    'invitation' => $invitation,
                    'session' => $session,
                    'owner' => $owner,
                    'content' => $content,
                    'invitation_url' => $invitationUrl,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send invitation email', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendInvitationAcceptedEmail($invitation, $session, $user): void
    {
        try {
            $owner = $this->userService->loadUser($session->ownerId);

            $email = (new Email())
                ->from($this->fromEmail)
                ->to($owner->email)
                ->subject('Collaboration Invitation Accepted')
                ->html($this->twig->render('emails/collaboration/invitation_accepted.html.twig', [
                    'invitation' => $invitation,
                    'session' => $session,
                    'user' => $user,
                    'owner' => $owner,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send invitation accepted email', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function cleanupSessionData($session): void
    {
        // Implement session cleanup logic here
        // For example: remove temporary files, clear cache, etc.
        $this->logger->info('Cleaning up session data', [
            'session_id' => $session->id,
        ]);
    }
}