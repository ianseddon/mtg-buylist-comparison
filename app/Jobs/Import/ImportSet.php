<?php

namespace App\Jobs\Import;

use App\Models\Reference\Set;

class ImportSet extends ImportJob
{
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        parent::__construct();

        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $set = Set::updateOrCreate(
            ['code' => $this->data['code']],
            ['name' => $this->data['name']]
        );

        // Import all the cards in the set.
        dispatch(new ImportCardsInSet($set, $this->data['cards']));
    }
}
