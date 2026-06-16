<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    public $chunk;
    public $fileNum;

    /**
     * @param \Illuminate\Support\Collection $chunk
     * @param int $fileNum
     */
    public function __construct($chunk, $fileNum)
    {
        // Обов'язково викликаємо батьківський конструктор, 
        // де визначається черга (onQueue)
        parent::__construct(); 
        
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }
}