<?php

namespace app\model;


abstract class MinimeEntity implements MinimeEntityInterface, \ArrayAccess
{
    /** Internal mapping without db fields @var null | array */
    protected $mapping;

    /** @var int */
    protected $id;

    /**
     * MinimeEntity constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getMappingByFieldName($name) {
        if ($this->mapping !== null) {
            $normalized = strtolower(str_replace('_', '', $name));
            $mapping = isset($this->mapping[$normalized]) ? $this->mapping[$normalized] : null;
        }

        if ($mapping === null) {
            throw new \RuntimeException('No mapping for the field [' . $name . ']!');
        }
        return $mapping;
    }

    public function getMapping() {
        return $this->mapping;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getData() {
        $data = array();
        if ($this->mapping !== null) {
            $maping = $this->mapping;
        }

        
        foreach ($maping as $fieldMapping) {
            $getter = 'get'.$fieldMapping['getset'];
            $data[$fieldMapping['field']] = $this->$getter();
        }
        
        return $data;
    }
    
    // ArrayAccess
    public function offsetSet($key, $value)
    {
        //if (is_null($key) && !isset($this->$key)) {
        //    return; //$this->storage[] = $value;
        //} else {
            $mapping = $this->getMappingByFieldName($key);
            $setter = 'set' . $mapping['getset'];            
            $this->$setter($value);
            //$this->$key = $value;
        //}
    }

    public function offsetExists($key)
    {   
        try {
            $mapping = $this->getMappingByFieldName($key);
        } catch (\Exception $e) {
            return false;
        }
        
        return true;
        //return isset($this->$key);
    }
 
    public function offsetUnset($key)
    {
        $mapping = $this->getMappingByFieldName($key);
        $setter = 'set' . $mapping['getset'];            
        $this->$setter(null);
        //unset($this->storage[$key]);
    }
    
    public function offsetGet($key)
    {
        try {
            $mapping = $this->getMappingByFieldName($key);
        } catch (\Exception $e) {
            return null;
        }

        $getter = 'get' . $mapping['getset'];
        
        $val = $this->$getter();
        //$val = $this->$key;
        if (is_callable($val)) {
            return $val($this);
        }
        return $val;
    }    
    //
}