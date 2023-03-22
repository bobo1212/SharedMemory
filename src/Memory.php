<?php

namespace Bobo1212\SharedMemory;


class Memory
{
    /**
     * @var false|resource
     */
    private $shmId;
    /**
     * @var int
     */
    private $size = 1024 * 100;
    /**
     * @var false|resource
     */
    private $sem;


    /**
     * @throws MemoryException
     */
    public function __construct(int $shmKey)
    {
        $this->shmId = shm_attach($shmKey, $this->size, 0644);
        if (false === $this->shmId) {
            throw new MemoryException('shm_attach error');
        }
        $this->sem = sem_get($shmKey);
        if (false === $this->sem) {
            throw new MemoryException('sem_get error');
        }
    }

    public function lock()
    {
        $lock = sem_acquire($this->sem);
        if (false === $lock) {
            throw new MemoryException('sem_acquire error');
        }
    }
    public function unLock(){
        $lock = sem_release($this->sem);
        if (false === $lock) {
            throw new MemoryException('sem_release error');
        }
    }
    /**
     * @throws MemoryException
     */
    public function write(int $key, $data)
    {
        $put = shm_put_var($this->shmId, $key, $data);
        if (false === $put) {
            throw new MemoryException('shm_put_var error');
        }
    }

    /**
     * @throws MemoryException
     * @throws MemoryExceptionNotFound
     */
    public function read($key)
    {
        if(false === shm_has_var($this->shmId, $key)){
            throw new MemoryExceptionNotFound('Key not found ' . $key);
        }
        $shmData = shm_get_var($this->shmId, $key);
        if (false === $shmData) {
            throw new MemoryException('shm_get_var read error');
        }
        return $shmData;
    }
}