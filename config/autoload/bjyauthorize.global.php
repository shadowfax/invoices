<?php
return array(
    'bjyauthorize' => array(

		// resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
		'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'people'   => array(),
				'expenses' => array(),
				'invoices' => array(),
				'invoices/items' => array(),
				'invoices/payments' => array(),
            ),
        ),
 
        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
        			// allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('biller'), 'people'),
                    array(array('accountant' , 'staff'), 'expenses'),
                    array(array('biller'), 'invoices/payments'),
                    array(array('biller'), 'invoices/items'),
                    array(array('biller', 'customer', 'accountant'), 'invoices'),
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                	
                
                )
            ),
        ),
	)
);