<?php

namespace app\model;


abstract class MinimeEntity implements \ArrayAccess
{
    protected $app;
    
    protected $mapper;
    
    protected $id;

    public function __construct($mapper, $app)
    {
        $this->mapper = $mapper;
        $this->app = $app;
    }

    public function getMappingByFieldName($name) {
        $mapping = $this->mapper->getMappingByFieldName($name);
        if ($mapping === null) {
            throw new \RuntimeException('No mapping for the field [' . $name . ']!');
        }
        return $mapping;
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
        $maping = $this->mapper->getMapping();
        
        foreach ($maping as $fieldMapping) {
            $getter = 'get'.$fieldMapping['getset'];
            $data[$fieldMapping['field']] = $this->$getter();
        }
        
        return $data;
    }
    
    // ArrayAccess
    public function offsetSet($key, $value)
    {
        if (is_null($key) && !isset($this->$key)) {
            return; //$this->storage[] = $value;
        } else {
            $mapping = $this->getMappingByFieldName($key);
            $setter = 'set' . $mapping['getset'];            
            $this->$setter($value);
            //$this->$key = $value;
        }
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