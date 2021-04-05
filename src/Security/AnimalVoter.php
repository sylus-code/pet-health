<?php

namespace App\Security;

use App\Entity\Animal;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AnimalVoter extends Voter
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
        // handle only Animal objects
        if (!$subject instanceof Animal) {
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
        /** @var Animal $animal * */

        if (is_array($subject)) {
            $animal = $subject[0];
        } else {
            $animal = $subject;
        }

        return $user === $animal->getUser();
    }
}