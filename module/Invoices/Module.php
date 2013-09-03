<?php
/**
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax/invoices
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Invoices;

// The namespace has not been loaded into the autoloader
// therefore we require the AbstractModule file
require_once __DIR__ . '/src/Invoices/Module/AbstractModule.php';

use Invoices\Module\AbstractModule;

use Invoices\Service\OptionsService;

use Zend\ModuleManager\ModuleManager;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
                
        // Add ACL information to the Navigation view helper
        $serviceManager = $e->getApplication()->getServiceManager();
        $authorize = $serviceManager->get('BjyAuthorize\Service\Authorize');
        $acl       = $authorize->getAcl();
        $role      = $authorize->getIdentity();
        \Zend\View\Helper\Navigation::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation::setDefaultRole($role);
    }
    
    public function getDir()
    {
    	return __DIR__;
    }
    
    public function getNamespace()
    {
    	return __NAMESPACE__;
    }
    
    /*
    public function init(ModuleManager $moduleManager) 
    { 
    	// If using 'init' ALWAYS call parent
        parent::init($moduleManager); 
    } 
    */
    
	public function getServiceConfig()
	{
		return array(
			'factories' => array(
	            // Keys are the service names
	            // Values are objects
	            'Invoices.Options' => function ($serviceManager) {
					$options = new OptionsService();
					$options->setServiceLocator($serviceManager);
					return $options;
	            }
        	),
			//'factories' => array(
			//	'invoices.service.client' => 'Invoices\Factory\ClientServiceFactory',
			//	'invoice_service.items'  => 'Invoices\Factory\ItemServiceFactory',
			//	'invoice_service.taxes'  => 'Invoices\Factory\TaxServiceFactory',
			//)
		);
	}
}
