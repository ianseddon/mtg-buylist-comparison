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
        parent::__construct();

        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // If we didn't get an override path, just use the default location.
        if (empty($this->filePath)) {
            $this->filePath = storage_path() . '/app/AllSets.json';
        }

        $stream = fopen($this->filePath, 'r');
        $listener = new ImportJsonConsumeSetListener();
        $this->parser = new \JsonStreamingParser\Parser(
            $stream,
            $listener
        );
        $this->parser->parse();
    }
}
