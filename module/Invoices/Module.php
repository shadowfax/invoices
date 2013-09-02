<?php
/**
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax/invoices
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Invoices;

use Invoices\Service\OptionsService;

use Zend\ModuleManager\ModuleManager;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // No layout for errors
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
             $result = $e->getResult();
             $result->setTerminal(TRUE);
		});
		
        // Force authentication
		$serviceManager = $e->getApplication()->getServiceManager();
		$auth           = $serviceManager->get('zfcuser_auth_service');
		
		$eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) use ($auth) {
            $match = $e->getRouteMatch();

            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                return;
            }
            // White list (Accesible routes without auth)
			$list = array('zfcuser/login');
			
            // Route is whitelisted
            $name = $match->getMatchedRouteName();
            if (in_array($name, $list)) {
                return;
            }

            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }

            // Redirect to the user login page, as an example
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'zfcuser/login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);
        
        // Add ACL information to the Navigation view helper
        $authorize = $serviceManager->get('BjyAuthorize\Service\Authorize');
        $acl       = $authorize->getAcl();
        $role      = $authorize->getIdentity();
        \Zend\View\Helper\Navigation::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation::setDefaultRole($role);
    }
    
    public function init(ModuleManager $moduleManager) 
    { 
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager(); 
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) { 
            $serviceManager = $e->getApplication()->getServiceManager(); 
            $options = $serviceManager->get('Invoices.Options');
            $theme   = $options->get('theme', 'default');
            $theme_path = __DIR__ . '/theme/' . $theme;
            
            $templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack'); 
            $templatePathResolver->setPaths(array($theme_path)); // here is your skin name 

            $viewModel = $e->getViewModel(); 
        	$viewModel->setTemplate('layout'); 
        }, 100); 
    } 

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
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
