<?php

namespace App\Security\User;

use PhpParser\Node\Stmt\Return_;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Ldap\Ldap;

class CasUserProvider implements UserProviderInterface
{
    
    
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {

/*
        $ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://ldap.uca.fr:636']);

        //dd($ldap);


        $ldap->bind();
$query = $ldap->query('dc=uca,dc=fr', '(&(objectclass=people)(uid=yoaraiso))');
$results = $query->execute();
        //dump($results);
foreach ($results as $entry) {
            echo "kk";
            dump($entry);
    // Do something with the results
}
        die;
        */

        if (!empty($identifier)) {
            $user = new User();
            $user->setUid($identifier);

            //vérfier dans le ldap si il fait parti du group admin BU invit
            $ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://ldap.uca.fr']);
            $ldap->bind();



            $infoCompteLdap = $ldap->query("dc=uca,dc=fr", "(&(cn=bu-invit-lecteur))")->execute()->toArray();

            $members = $infoCompteLdap[0]->getAttributes()['member'];

            $listRoles = [];

            //"uid=nidelpeu,ou=people,dc=uca,dc=fr"

            if (in_array($identifier, $members)) {

                //extraction de la chaine 'uid' pour comparer
                $listRoles[] = 'ROLE_USER';
            }


            if ($infoCompteLdap = $ldap->query("dc=uca,dc=fr", "(&(cn=bu-invit-lecteur))")->execute()->toArray())
                $listRoles [] = 'ROLE_ADMIN';

            if(!empty($listRoles))
                $user->setRoles($listRoles);


                return $user; 

        } else
            throw new UserNotFoundException();
    }

        
        

    /**
     * @deprecated since Symfony 5.3, loadUserByIdentifier() is used instead
     */
    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * Upgrades the hashed password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: when hashed passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newHashedPassword);
    }
}
?>