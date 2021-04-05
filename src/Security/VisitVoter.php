<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Visit;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VisitVoter extends Voter
{
    public const ACCESS = 'access';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject): bool
    {
        if (is_array($subject) && count($subject) === 0){
            return false;
        }
        if (is_array($subject)){
            $subject = $subject[0];
        }
        // handle only Visit objects
        if (!$subject instanceof Visit){
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

        if (!$user instanceof User){
            // if user is not logged in, deny access
            return false;
        }
        /** @var Visit $visit */
        if (is_array($subject)){
            $visit = $subject[0];
        }else {
            $visit = $subject;
        }

        return $user === $visit->getAnimal()->getUser();
    }
}