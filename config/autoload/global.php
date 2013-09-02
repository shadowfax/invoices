<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(

	// Doctrine
	'doctrine' => array(
		'connection' => array(
			'orm_default' =>array(
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				'params' => array(
					'host'     => 'localhost',
					'port'     => '3306',
					// Credentials should be stored in local.php 
					// to avoid commiting it.
					//'user'     => '<YOUR USERNAME>',
					//'password' => '<YOUR PASSWORD>',
					'dbname'   => 'invoices',
				)
			)
		)
	),
 
);
