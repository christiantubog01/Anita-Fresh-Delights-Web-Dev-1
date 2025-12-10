<?php

namespace App\EventListener;

use App\Service\ActivityLogger;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginLogoutListener
{
    private ActivityLogger $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public function onLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $this->activityLogger->log('LOGIN', 'User logged in: ' . $user->getUserIdentifier());
    }

    public function onLogout(LogoutEvent $event): void
    {
        $user = $event->getToken()->getUser();
        $this->activityLogger->log('LOGOUT', 'User logged out: ' . $user->getUserIdentifier());
    }
}
