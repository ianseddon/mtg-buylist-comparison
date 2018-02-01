<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Import\ImportSet;
use App\Jobs\Import\ImportCardsInSet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Reference\Set;

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
    public function test_it_doesnt_create_duplicate_sets_with_same_code()
    {
        $setData = $this->makeSampleSet();

        (new ImportSet($setData))->handle();
        (new ImportSet($setData))->handle();

        $count = Set::where('code', $setData['code'])->count();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function test_it_updates_changed_sets()
    {
        $setData = $this->makeSampleSet();
        (new ImportSet($setData))->handle();

        $oldName = $setData['name'];
        $setData['name'] = 'Changed Name!';
        (new ImportSet($setData))->handle();

        $this->assertDatabaseHas('sets', ['code' => $setData['code'], 'name' => $setData['name']]);
        $this->assertDatabaseMissing('sets', ['code' => $setData['code'], 'name' => $oldName]);
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
        $setData['code'] = 'TST';

        return $setData;
    }
}
