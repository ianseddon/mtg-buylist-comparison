<?php

namespace App\Jobs\Import;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Reference\Set;
use App\Models\Reference\Card;

class ImportCardsInSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $set;
    protected $cards;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Set $set, array $cards)
    {
        $this->set = $set;
        $this->cards = $cards;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->cards as $cardData) {
            // Skip cards with no multiverse id.
            if (!isset($cardData['multiverseid'])) {
                continue;
            }

            // Create the card model from import data, or update it if it already exists.
            $this->set
                ->cards()
                ->updateOrCreate(
                    ['multiverse_id' => $cardData['multiverseid']],
                    ['name' => $cardData['name']]
                );
        }
    }
}
