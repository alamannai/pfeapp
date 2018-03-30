<?php

namespace AppBundle\Service;


use FOS\UserBundle\Doctrine\UserManager;

class MyUserManager extends UserManager
{
    /**
     * @inheritdoc
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        $user = parent::findUserByUsernameOrEmail($usernameOrEmail);
        if (null === $user) {
            $userAddOnEmailRepo = $this->objectManager->getRepository('AppBundle:UserAddOnEmail');
            $userAddOnEmail = $userAddOnEmailRepo->findOneBy(['email' => $usernameOrEmail]);
            if ($userAddOnEmail) {
                $user = $userAddOnEmail->getUser();
            }
        }

        return $user;
    }
}