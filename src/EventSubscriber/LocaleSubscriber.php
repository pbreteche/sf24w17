<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function preferredLanguage(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $preferredLocale = $request->getPreferredLanguage(['fr', 'en_US', 'en']);
        if ($preferredLocale) {
            $request->setLocale($preferredLocale);
        }
    }

    public function inSession(RequestEvent $event): void
    {
        // * gérer si une locale a été stockée en session
        // * proposer dans l'interface utilisateur un moyen de définir sa locale
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['preferredLanguage', 24],
                ['inSession', 20],
            ]
        ];
    }
}
