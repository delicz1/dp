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
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author springer
 */
class ImapAuthenticator implements SimpleFormAuthenticatorInterface {

    const SERVER = 'imap.utb.cz';

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

    /**
     * @param TokenInterface        $token
     * @param UserProviderInterface $userProvider
     * @param                       $providerKey
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {

        $userName = $token->getUsername();
        $password = $token->getCredentials();
        $passwordValid = false;
        try {
            $repo = $this->doctrine->getRepository("ProjBaseBundle:User");
            $user = null;//$repo->findOneBy([User::COLUMN_EMAIL => $userName]);
            if ($user instanceof User) {
                if ($user->getStatus() == User::STATUS_DELETED) {
                    throw new AuthenticationException('login.user.deleted');
                }
                $passwordValid = $this->encoder->isPasswordValid($user, $password);
            }
            if (!$passwordValid) {
                //Oprava padu aplikace - chyba v php-fpm -> core dump
                $imapResult = (string) exec('php -r "echo @imap_open(\"{'.self::SERVER.':993/imap/ssl/novalidate-cert}\", \"'. $userName.'\", \"'.$password.'\");"');
//                $imapResult = @imap_open("{".self::SERVER.":993/imap/ssl/novalidate-cert}", $userName, $password);
                if (strpos($imapResult, 'Resource') !== false) { //Uzivatel je prihlasen pomoci imapu
                    $passwordValid = true;
                    if (! $user instanceof User) { //uzivatel neexistuje v systemu -> vytvorim ho
                        $user = new User();
                        $user->setEmail($userName);
                        $user->setPasswd(sha1($password));
                        $user->setStatus(User::STATUS_ACTIVE);
                        $user->setRole(User::ROLE_USER);
                        $user->setSurname($userName);

                        $em = $this->doctrine->getManager();
                        $em->persist($user);
                        $em->flush();
                    }
                } else { //neni mozne se prihlasit
                    throw new AuthenticationException('login.error');
                }
            }
        } catch (\Exception $e) {
            throw new AuthenticationException('login.error');
        }
        if ($passwordValid) {
            return new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                $providerKey,
                $user->getRoles()
            );
        }
        throw new AuthenticationException('login.error');
    }

    /**
     * @param TokenInterface $token
     * @param                $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param         $username
     * @param         $password
     * @param         $providerKey
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}