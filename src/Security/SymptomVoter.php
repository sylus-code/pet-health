<?php

namespace App\Security;

use App\Entity\Symptom;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SymptomVoter extends Voter
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
        // handle only Symptom objects
        if (!$subject instanceof Symptom) {
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
        /** @var Symptom $symptom */
        if (is_array($subject)) {
            $symptom = $subject[0];
        } else {
            $symptom = $subject;
        }

        return $user === $symptom->getAnimal()->getUser();
    }
}