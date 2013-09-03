<?php
/**
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax/invoices
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */
namespace Invoices\Module;

use Zend\Mvc\MvcEvent;

use InvalidArgumentException;
use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ApplicationInterface;

/**
 * Any additional module that is part of the Invoices application should
 * extend this abstract class as it sets such things as theme management,
 * mandatory authentication, etc...
 */
abstract class AbstractModule implements
    AutoloaderProviderInterface,
    LocatorRegisteredInterface
{
    abstract public function getDir();
    abstract public function getNamespace();

	public function init(ModuleManager $moduleManager) 
    { 
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        // No layout for errors
        $sharedEvents->attach($this->getNamespace(), MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
             $result = $e->getResult();
             $result->setTerminal(TRUE);
		});
		
		// Themes and authentication
        $dir = $this->getDir(); 
        $sharedEvents->attach($this->getNamespace(), MvcEvent::EVENT_DISPATCH, function($e) use ($dir) {
        	$serviceManager = $e->getApplication()->getServiceManager(); 
        	
        	// Force authentication
        	$auth = $serviceManager->get('zfcuser_auth_service');
            if (!$auth->hasIdentity()) {
                $e->getTarget()->redirect()->toRoute('zfcuser/login');
            }
            
        	// Theme manager 
            $options        = $serviceManager->get('Invoices.Options');
            $theme          = $options->get('theme', 'default');
            $theme_path     = $dir . '/theme/' . $theme;
            
            $templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack'); 
            $templatePathResolver->setPaths(array($theme_path)); // here is your skin name 

            $viewModel = $e->getViewModel(); 
        	$viewModel->setTemplate('layout');
        }, 100);
    }
    

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->getNamespace() => $this->getDir() . '/src/' . $this->getNamespace(),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include $this->getDir() . '/config/module.config.php';
    }  

}
