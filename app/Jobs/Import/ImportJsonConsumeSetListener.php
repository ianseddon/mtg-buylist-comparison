<?php

namespace App\Jobs\Import;

use JsonStreamingParser\Listener\SubsetConsumerListener;

class ImportJsonConsumeSetListener extends SubsetConsumerListener
{
    /**
     * Consume a parsed JSON object once it's finished streaming.
     *
     * @param array $data
     * @return void
     */
    protected function consume($data)
    {
        // Only consume sets.
        if (!$this->shouldConsume($data)) {
            return;
        }

        dispatch(new ImportSet($data));
    }

    /**
     * Check if we should consume the given object.
     *
     * @param [type] $data
     * @return void
     */
    protected function shouldConsume($data)
    {
        return isset($data['code']) && isset($data['cards']);
    }
}
