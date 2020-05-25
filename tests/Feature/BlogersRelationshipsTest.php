<?php

namespace Tests\Feature;

use App\Bloger;
use App\News;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BlogersRelationshipsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @watch
     */
    public function it_returns_a_relationship_to_blogers_adhering_to_json_api_spec()
    {
        $bloger = factory(Bloger::class)->create();
        $news = factory(News::class, 3)->create();
//        $bloger->news()->sync($news->only('id'));
        $this->mysync($bloger, $news);
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->getJson('/api/blogers/1', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => '1',
                    'type' => 'blogers',
                    'relationships' => [
                        'news' => [
                            'links' => [
                                'self' => route(
                                    'blogers.relationships.news',
                                    ['bloger' => $bloger->id]
                                ),
                                'related' => route(
                                    'blogers.news',
                                    ['bloger' => $bloger->id]
                                ),
                            ],
                            'data' => [
                                [
                                    'id' => $news->get(0)->id,
                                    'type' => 'news'
                                ],
                                [
                                    'id' => $news->get(1)->id,
                                    'type' => 'news'
                                ],
                                [
                                    'id' => $news->get(2)->id,
                                    'type' => 'news'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function mysync($bloger, $news)
    {
        foreach($news as $new)
        {
            DB::table('bloger_news')->insert(
                ['bloger_id' => $bloger->id, 'news_id' => $new->id]);
        }
    }
}
