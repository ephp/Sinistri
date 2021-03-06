<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Log;

use Doctrine\ORM\EntityRepository;
use Ephp\Bundle\WsInvokerBundle\Exceptions\SessionExpiredException;

/**
 * LogAuthenticationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogAuthenticationRepository extends EntityRepository {

    /**
     *
     * @param type $username
     * @param \DateTime $date
     * @return Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication|boolean 
     */
    public function chechActiveSession($username, \DateTime $date) {
        try {
            $q = $this->getEntityManager()->createQuery('
            SELECT a 
              FROM Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication a 
             WHERE a.username = :username 
               AND a.token IS NOT NULL
               AND a.expired_at > :date
               ');
            $q->setParameter('username', $username);
            $q->setParameter('date', $date);
            $service = $q->getSingleResult();
            return $service;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param type $username
     * @param \DateTime $date
     * @return Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication|boolean 
     */
    public function checkToken($token) {
        $q = null;
        try {
            $q = $this->getEntityManager()->createQuery('
            SELECT a 
              FROM Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication a 
             WHERE a.token = :token 
               AND a.expired_at > :date
               ');
            $q->setParameter('token', $token);
            $q->setParameter('date', new \DateTime());
            $service = $q->getSingleResult();
            return $service;
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new SessionExpiredException();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $username
     * @param \DateTime $date
     * @return Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication|boolean 
     */
    public function checkOrCreateToken($user, $persist) {
        $user = $user instanceof \Symfony\Component\Security\Core\User\User ? $user->getUsername() : ($user ? (is_string($user) ? $user : $user->__toString()) : 'System');
        
        $q = null;
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $now->sub(new \DateInterval('PT1H'));
        try {
            $q = $this->getEntityManager()->createQuery('
            SELECT a 
              FROM Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication a 
             WHERE a.username = :username 
               AND a.expired_at > :date
             ORDER BY a.expired_at DESC
               ');
            $q->setParameter('username', $user);
            $q->setParameter('date', $now);
            $q->setMaxResults(1);
            $service = $q->getSingleResult();
            return $service;
        } catch (\Doctrine\ORM\NoResultException $e) {
            //Calcolo del token
            $md5User = md5($user);
            $token = substr($md5User, 0, 16) . md5('es' . time()) . substr($md5User, 16);
            //Calcolo data di autenticazione e data di fine sessione
            $expired = new \DateTime('now', $zona);
            $expiredInterval = new \DateInterval('PT1H');
            $expired = $expired->add($expiredInterval);
            $em = $this->getEntityManager();
            try {
                $now = new \DateTime('now', $zona);
                $em->beginTransaction();
                //Memorizzo su log
                $log = new LogAuthentication();
                $log->setUsername($user);
                $log->setToken($token);
                $log->setMessage('Basic Authentication');
                $log->setCode(200);
                $log->setRequestAt($now);
                $log->setExpiredAt($expired);
                if($persist) {
                    $em->persist($log);
                    $em->flush();
                }
                $em->commit();
                return $log;
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

}