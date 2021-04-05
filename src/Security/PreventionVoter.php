<?php

namespace App\Security;

use App\Entity\Prevention;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PreventionVoter extends Voter
{
    public const ACCESS = 'access';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject): bool
    {
        if (is_array($subject) && count($subject) === 0) {
            return false;
        }
        if (is_array($subject)) {
            $subject = $subject[0];
        }
        // handle only Prevention objects
        if (!$subject instanceof Prevention) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // if user is not logged in, deny access
            return false;
        }
        /** @var Prevention $prevention */
        if (is_array($subject)) {
            $prevention = $subject[0];
        } else {
            $prevention = $subject;
        }

        return $user === $prevention->getAnimal()->getUser();
    }
}