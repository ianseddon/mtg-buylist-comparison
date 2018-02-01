<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Import\ImportSet;
use App\Jobs\Import\ImportCardsInSet;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportSetTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        Bus::fake();
    }

    /** @test */
    public function test_it_creates_a_set_from_data()
    {
        $setData = $this->makeSampleSet();

        (new ImportSet($setData))->handle();

        $this->assertDatabaseHas('sets', ['name' => $setData['name'], 'code' => $setData['code']]);
    }

    /** @test */
    public function test_it_dispatches_a_job_for_cards()
    {
        $setData = $this->makeSampleSet();

        // When
        (new ImportSet($setData))->handle();

        // Then
        Bus::assertDispatched(ImportCardsInSet::class);
    }

    /**
     * Create test set data that mirrors the
     * expected source JSON structure.
     */
    protected function makeSampleSet()
    {
        $setStub = file_get_contents(resource_path('stubs/setWithNoCards.json'));
        $setData = json_decode($setStub, true);

        return $setData;
    }
}
