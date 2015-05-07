<?php

namespace Proj\Base\Security;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Proj\Base\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author springer
 */
class ImapAuthenticator implements SimpleFormAuthenticatorInterface {

    private $encoder;
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @param UserPasswordEncoderInterface $encoder
     * @param Registry                     $doctrine
     */
    public function __construct(UserPasswordEncoderInterface $encoder, Registry $doctrine) {
        $this->encoder = $encoder;
        $this->doctrine = $doctrine;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {

        $userName = $token->getUsername();
        $password = $token->getCredentials();

        try {

            $mail_server = "imap.utb.cz";
//            $mail_username = "d_springer@fai.utb.cz" ;
//            $mail_password = "1icJwEDk7" ;
            $imapResult = @imap_open("{".$mail_server.":993/imap/ssl/novalidate-cert}", $userName, $password);
            $repo = $this->doctrine->getRepository("ProjBaseBundle:User");
            $user = $repo->findOneBy([User::COLUMN_EMAIL => $userName]);

            if ($imapResult) {
                if (! $user instanceof User) {
                    $user = new User();
                    $user->setEmail($userName);
                    $user->setPasswd(sha1($password));
                    $user->setStatus(User::STATUS_ACTIVE);
                    $user->setRole(User::ROLE_USER);

                    $em = $this->doctrine->getManager();
                    $em->persist($user);
                    $em->flush();
                }
            } elseif ($user instanceof User) {
                $passwordValid = $this->encoder->isPasswordValid($user, $password);
                if (! $passwordValid) {
                    throw new AuthenticationException('Invalid username or password');
                }
            }
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Invalid username or password');
        }

//        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());
//        dump($token->getCredentials());
//        if ($passwordValid) {
//            $currentHour = date('G');
//            if ($currentHour < 14 || $currentHour > 16) {
//                throw new AuthenticationException(
//                    'You can only log in between 2 and 4!',
//                    100
//                );
//            }

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}