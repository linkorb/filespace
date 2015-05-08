<?php

namespace FileSpace\Common;

class File
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
    
    private $size_original;
    
    public function getSizeOriginal()
    {
        return $this->size_original;
    }
    
    public function setSizeOriginal($size_original)
    {
        $this->size_original = $size_original;
    }
    
    private $size_storage;
    
    public function getSizeStorage()
    {
        return $this->size_storage;
    }
    
    public function setSizeStorage($size_storage)
    {
        $this->size_storage = $size_storage;
    }
    
    private $data_hash;
    
    public function getDataHash()
    {
        return $this->data_hash;
    }
    
    public function setDataHash($data_hash)
    {
        $this->data_hash = $data_hash;
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
