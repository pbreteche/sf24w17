<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public const MANAGED_LOCALE = ['fr', 'en_US', 'en'];

    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function preferredLanguage(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $preferredLocale = $request->getPreferredLanguage(static::MANAGED_LOCALE);
        if ($preferredLocale) {
            $request->setLocale($preferredLocale);
        }
    }

    public function inSession(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $event->getRequest()->getSession();
        if (
            ($langQuery = $request->query->getString('lang'))
            && in_array($langQuery, static::MANAGED_LOCALE)
        ) {
            $session->set('locale', $langQuery);
        }

        if ($sessionLocale = $session->get('locale')) {
            $request->setLocale($sessionLocale);
        }

        // * gérer si une locale a été stockée en session
        // * proposer dans l'interface utilisateur un moyen de définir sa locale
    }

    public function userLocale(RequestEvent $requestEvent): void
    {
        $user = $this->security->getUser();
        if ($user instanceof User && $user->getLocale()) {
            $requestEvent->getRequest()->setLocale($user->getLocale());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['preferredLanguage', 24],
                ['inSession', 20],
                ['userLocale', 18],
            ]
        ];
    }
}
