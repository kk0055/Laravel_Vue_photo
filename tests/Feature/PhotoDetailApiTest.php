<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Photo;

class PhotoDetailApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function should_正しい構造のJSONを返却する()
    {
        factory(Photo::class)->create();
        $photo = Photo::first();

        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
            ]);
    }
}
