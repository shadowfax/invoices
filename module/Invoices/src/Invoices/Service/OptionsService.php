<?php
/**
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax/invoices
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Invoices\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

class OptionsService implements ServiceLocatorAwareInterface
{
	/**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
	/**
	 * @var EntityManager
     */
    protected $entityManager;
    
	/**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
	/**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

	/**
     * Returns an instance of the Doctrine entity manager loaded from the service 
     * locator
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }
    
    /**
     * Magic getter for options
     * 
     * @param string $option The option key
     */
    public function __get($option)
    {
    	return $this->get($option);	
    }
    
    /**
     * Magic setter for options.
     * 
     * @param string $option The option key
     * @param string $value  The option value
     */
    public function __set($option, $value)
    {
    	$this->set($option, $value);
    }
    
    /**
     * Magic method to check if an option is set.
     * 
     * @param unknown_type $option
     */
    public function __isset($option)
    {
    	$entity = $this->getEntityManager()->getRepository('Invoices\Entity\Options')->find($option);
    	if (null === $entity) {
    		return $false;
    	}
    	$value = $entity->getValue();
    	return isset($value);
    }
    
    /**
     * Get an option.
     * 
     * @param  string $option
     * @param  string $default
     * @return string
     */
    public function get($option, $default = null)
    {
    	$entity = $this->getEntityManager()->getRepository('Invoices\Entity\Option')->find($option);
    	if (null === $entity) {
    		if (null === $default) {
    			throw new \Exception('Option ' . $option . ' is not set');
    		} else {
    			return $default;
    		}
    	}
    	return $entity->getValue();
    }
    
    /**
     * Set an option.
     * 
     * @param string $option The option key
     * @param string $value  The option value
     */
    public function set($option, $value)
    {
    	$entity = $this->getEntityManager()->getRepository('Invoices\Entity\Option')->find($option);
    	if (null === $entity) {
    		$entity = new \Invoices\Entity\Option();
    		$entity->setKey($option)
    		       ->setValue($value);
    	}
    	$this->getEntityManager()->persist($entity);
    	$this->getEntityManager()->flush($entity);
    }
    
    /**
     * Check if the option exists.
     * 
     * @param string $option The option key
     * @return boolean       Returns true if the option exists or false if it is not present
     */
    public function has($option)
    {
    	$entity = $this->getEntityManager()->getRepository('Invoices\Entity\Option')->find($option);
    	if (null === $entity) {
    		return false;
    	}
    	return true;
    }
}