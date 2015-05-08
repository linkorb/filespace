<?php

namespace FileSpace\Common;

class Space
{
    private $key;
    
    public function __construct($key)
    {
        $this->key = $key;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    private $created_at;
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    
    private $deleted_at;
    
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }
    
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }
    
    private $updated_at;
    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
    
    
    
}
