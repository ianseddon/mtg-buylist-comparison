<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Import\ImportSet;
use App\Jobs\Import\ImportFromJson;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportFromJsonTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake();
        Bus::fake();
    }

    /** @test */
    public function test_it_dispatches_a_job_for_sets()
    {
        $this->runImportJob();

        Bus::assertDispatched(ImportSet::class);
    }

    /**
     * Run the import job synchronously
     * (without queue) for testing.
     */
    protected function runImportJob()
    {
        (new ImportFromJson(resource_path('stubs/fullData.json')))->handle();
    }
}
