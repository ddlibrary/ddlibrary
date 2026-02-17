<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\ResourceController;
use App\Models\DownloadCount;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceDownloadCounterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_download_count_row_and_returns_201(): void
    {
        $this->withoutMiddleware();
        $resource = Resource::factory()->create();

        $attachment = ResourceAttachment::factory()->create([
            'resource_id' => $resource->id,
        ]);

        $payload = [
            'resource_id' => $resource->id,
            'file_id' => $attachment->id
        ];

        $response = $this->postJson(action([ResourceController::class, 'resourceDownloadCounter']), $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'id',
        ]);

        $this->assertDatabaseHas((new DownloadCount())->getTable(), [
            'resource_id' => $resource->id,
            'file_id' => $attachment->id,
            'user_id' => 0,
        ]);
    }

    public function test_it_rejects_file_id_that_does_not_belong_to_resource(): void
    {
        $this->withoutMiddleware();
        $resourceA = Resource::factory()->create();
        $resourceB = Resource::factory()->create();

        $attachmentOnB = ResourceAttachment::factory()->create([
            'resource_id' => $resourceB->id,
        ]);

        $payload = [
            'resource_id' => $resourceA->id,
            'file_id' => $attachmentOnB->id,
        ];

        $response = $this->postJson(action([ResourceController::class, 'resourceDownloadCounter']), $payload);

        $response->assertStatus(422);

        $this->assertDatabaseCount((new DownloadCount())->getTable(), 0);
    }
}
