<?php

namespace App\Jobs\Import;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Reference\Set;

class ImportSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
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
