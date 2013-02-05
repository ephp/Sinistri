<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Config;

use Doctrine\ORM\EntityRepository;
use Ephp\Bundle\WsInvokerBundle\Exceptions\ServiceNotFoundException;

/**
 * ServiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiceRepository extends EntityRepository {

    public function getService($group_name, $service_name) {
        try {
            $q = $this->getEntityManager()->createQuery('
            SELECT s 
              FROM Ephp\Bundle\WsInvokerBundle\Entity\Config\Service s 
              JOIN s.host h
              JOIN h.group g
             WHERE s.name = :service 
               AND g.name = :group
               ');
            $q->setParameter('service', $service_name);
            $q->setParameter('group', $group_name);
            $service = $q->getSingleResult();
            return $service;
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new ServiceNotFoundException($group_name, $service_name);
        } catch (\Exception $e) {
            throw $e;
        }
    }

}