<?php
/**
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax/invoices
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */
namespace Invoices\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Options entity.
 * Gets or sets the options for invoicing.
 *
 * @ORM\Entity
 * @ORM\Table(name="options")
 *
 */
class Option
{	
	/**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=64)
     */
    protected $key;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $value;
    
	/**
     * Gets the option key.
     * 
     * @return string
     */
    public function getKey()
    {
    	return $this->key;
    }
    
    /**
     * Sets the option key.
     * 
     * @param string $key
     * @return Invoices\Entity\Option
     */
    public function setKey($key)
    {
    	$this->key = $key;
    	return $this;
    }
    
    /**
     * Gets the option value.
     * 
     * @return string
     */
    public function getValue()
    {
    	return $this->value;
    }
    
    /**
     * Sets the option value.
     * 
     * @param string $value
     * @return Invoices\Entity\Option
     */
    public function setValue($value)
    {
    	$this->value = $value;
    	return $this;
    }
    
}