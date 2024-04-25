<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorshipVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'ENTITY_AUTHOR' === $attribute
            && method_exists($subject, 'getAuthoredBy')
        ;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $user === $subject->getAuthoredBy();
    }
}
