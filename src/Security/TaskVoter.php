<?php
// src/Security/TaskVoter.php
namespace App\Security;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    // these strings are just unique IDs for the permissions
    public const CREATE = 'TASK_CREATE';
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // only vote on TASK_* attributes on a Tasks object
        return in_array($attribute, [self::CREATE, self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Task;
    }

    /**
     * @param string   $attribute  the permission to check (VIEW, EDIT, etc)
     * @param Task    $task       the subject
     * @param TokenInterface $token the security token
     */
    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        // get the current User (or null if anon)
        $user = $token->getUser();
        if (null === $user) {
            // no logged-in user can't do anything
            return false;
        }

        // only the owner (UserIDRef) can view/edit/delete
        /** @var \App\Entity\User|null $user */
        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
            case self::DELETE:
                return $task->getUserRef()->getId() === $user->getId();
        }

        // no other attributes are supported
        return false;
    }
}
