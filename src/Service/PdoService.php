<?php

namespace FileSpace\Service;

use FileSpace\Common\ServiceInterface;
use FileSpace\Common\Space;
use FileSpace\Common\File;

use ObjectStorage\Adapter\StorageAdapterInterface;
use PDO;
use RuntimeException;

class PdoService implements ServiceInterface
{
    private $pdo;
    private $storage;
    private $prefix = '';
    
    public function __construct(PDO $pdo, StorageAdapterInterface $storage, $prefix = '')
    {
        $this->pdo = $pdo;
        $this->storage = $storage;
        $this->prefix = '';
    }
    
    public function hasSpace($spacekey)
    {
        $statement = $this->pdo->prepare(
            "SELECT name FROM filespace
            WHERE space_key=:space_key LIMIT 1"
        );
        $statement->execute(
            array(
                'space_key' => $spacekey,
            )
        );
        if ($statement->rowCount()>0) {
            return true;
        }
        return false;
    }

    public function getSpace($spacekey)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM filespace
            WHERE space_key=:space_key LIMIT 1"
        );
        $statement->execute(
            array(
                'space_key' => $spacekey,
            )
        );
        if ($statement->rowCount() == 0) {
            throw new RuntimeException("No such space key: " . $spacekey);
        }
        $row = $statement->fetch();
        $space = $this->row2space($row);
        return $space;
    }
    
    private function row2space($row)
    {
        $space = new Space($row['space_key']);
        $space->setName($row['name']);
        $space->setCreatedAt($row['created_at']);
        $space->setUpdatedAt($row['updated_at']);
        $space->setDeletedAt($row['deleted_at']);
        
        return $space;
    }

    public function createSpace($spacekey)
    {
        return $this->getSpace($spacekey);
    }

    public function deleteSpace($spacekey)
    {
        //TODO
    }
    
    
    
    
    
    
    public function hasFile(Space $space, $filekey)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM filespace_file
            WHERE space_key=:space_key AND file_key=:file_key ORDER BY created_at DESC LIMIT 1"
        );
        $statement->execute(
            array(
                'space_key' => $space->getKey(),
                'file_key' => $filekey,
            )
        );
        if ($statement->rowCount()>0) {
            return true;
        }
        return false;
    }
    
    public function getFile(Space $space, $filekey)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM filespace_file
            WHERE space_key=:space_key AND file_key=:file_key ORDER BY created_at DESC LIMIT 1"
        );
        $statement->execute(
            array(
                'space_key' => $space->getKey(),
                'file_key' => $filekey,
            )
        );
        $row = $statement->fetch();
        $file = $this->row2file($row);
        return $file;
    }
    
    public function getFiles(Space $space)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM filespace_file
            WHERE space_key=:space_key LIMIT 1"
        );
        $statement->execute(
            array(
                'space_key' => $space->getKey(),
            )
        );
        $files = array();
        $rows = $statement->fetchAll();
        foreach ($rows as $row) {
            $files[] = $this->row2file($row);
        }
        return $files;
    }
    
    private function row2file($row)
    {
        $file = new File($row['file_key']);
        $file->setCreatedAt($row['created_at']);
        $file->setDeletedAt($row['deleted_at']);
        $file->setSizeOriginal($row['size_original']);
        $file->setSizeStorage($row['size_storage']);
        $file->setDataHash($row['data_hash']);
        
        
        return $file;
    }
    
    public function createFile(SpaceInterface $space, $filekey)
    {
        return $this->getFile($filekey);
    }
    
    public function deleteFile(SpaceInterface $space, $filekey)
    {
        //TODO
    }
    
    
    public function upload(Space $space, $filekey, $filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("Can't upload non-existant file: " . $filename);
        }
        $data = file_get_contents($filename);
        $hash = sha1($data);
        
        $file = new File($filekey);
        $file->setCreatedAt(time());
        $file->setUpdatedAt(time());
        $file->setDataHash($hash);
        $file->setSizeOriginal(strlen($data));
        $file->setSizeStorage(strlen($data));
        
        $this->storage->setData($hash, $data);
        $this->persistFile($space, $file);
    }

    public function download(Space $space, $filekey, $filename)
    {
        $file = $this->getFile($space, $filekey);
        print_r($file);
        $hash = $file->getDataHash();
        $data = $this->storage->getData($hash);
        file_put_contents($filename, $data);
    }
    
    private function persistFile(Space $space, File $file)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO filespace_file
             (space_key, file_key, data_hash, size_original, size_storage, created_at)
             VALUES
             (:space_key, :file_key, :data_hash, :size_original, :size_storage, :created_at)"
        );
        $statement->execute(
            array(
                'space_key' => $space->getKey(),
                'file_key' => $file->getKey(),
                'data_hash' => $file->getDataHash(),
                'size_original' => $file->getSizeOriginal(),
                'size_storage' => $file->getSizeStorage(),
                'created_at' => $file->getCreatedAt()
            )
        );
        
    }
}
