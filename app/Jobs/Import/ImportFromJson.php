<?php

namespace App\Jobs\Import;

class ImportFromJson extends ImportJob
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath = null)
    {
        // If we didn't get an override path, just use the default location.
        if (empty($filePath)) {
            $filePath = storage_path() . '/app/AllSets.json';
        }

        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stream = fopen($this->filePath, 'r');
        $listener = new ImportJsonConsumeSetListener();
        $this->parser = new \JsonStreamingParser\Parser(
            $stream,
            $listener
        );
        $this->parser->parse();
    }
}
