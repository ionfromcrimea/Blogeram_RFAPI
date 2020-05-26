<?php

namespace Tests\Feature;

use App\Bloger;
use App\Exceptions\Handler;
use App\News;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Request;
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
    public function it_returns_a_relationship_to_news_adhering_to_json_api_spec()
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

    /**
     * @test
     * @watch
     */
    public function a_relationship_link_to_news_returns_all_related_news_as_resource_id_objects()
    {
        $bloger = factory(Bloger::class)->create();
        $news = factory(News::class, 3)->create();
//        $bloger->authors()->sync($news->pluck('id'));
        $this->mysync($bloger, $news);
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->getJson('/api/blogers/1/relationships/news', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => '1',
                        'type' => 'news',
                    ],
                    [
                        'id' => '2',
                        'type' => 'news',
                    ],
                    [
                        'id' => '3',
                        'type' => 'news',
                    ],
                ]
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function it_can_modify_relationships_to_news_and_add_new_relationships()
    {
        $bloger = factory(Bloger::class)->create();
        $news = factory(News::class, 5)->create();
        $this->mysync($bloger, $news);
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->patchJson('/api/blogers/1/relationships/news', [
            'data' => [
                [
                    'id' => '2',
                    'type' => 'news',
                ],
                [
                    'id' => '5',
                    'type' => 'news',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(204);
        $this->assertDatabaseHas('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 2,
        ])->assertDatabaseHas('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 5,
        ])->assertDatabaseMissing('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 1,
        ])->assertDatabaseMissing('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 3,
        ])->assertDatabaseMissing('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 4,
        ])->assertDatabaseMissing('bloger_news', [
            'bloger_id' => 1,
            'news_id' => 6,
        ]);
    }

    /**
     * @test
     * @watch
     */
    public function it_returns_a_404_not_found_when_trying_to_add_relationship_to_a_non_existing_()
    {
        $bloger = factory(Bloger::class)->create();
        $news = factory(News::class, 5)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->patchJson('/api/blogers/1/relationships/news', [
            'data' => [
                [
                    'id' => '5',
                    'type' => 'news',
                ],
                [
                    'id' => '6',
                    'type' => 'news',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(404)->assertJson([
            'errors' => [
                [
                    'title' => 'Not Found Http Exception',
                    'details' => 'Resource not found',
                ]
            ]
        ]);
    }

    /**
     * @test
     * @watch
     */
    public function it_converts_a_query_exception_into_a_not_found_exception()
    {
        /** @var Handler $handler */
        $handler = app(Handler::class);
        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'application/vnd.api+json');
        $exception = new QueryException('select ? from ?', ['name', 'nothing'], new \Exception(''));
        $response = $handler->render($request, $exception);
        TestResponse::fromBaseResponse($response)->assertJson([
            'errors' => [
                [
                    'title' => 'Not Found Http Exception',
                    'details' => 'Resource not found',
                ]
            ]
        ]);
    }

//    **************************************************************
    public function mysync($bloger, $news)
    {
        DB::table('bloger_news')->where('bloger_id', '=', $bloger->id)->delete();
        foreach ($news as $new) {
            DB::table('bloger_news')->insert(
                ['bloger_id' => $bloger->id, 'news_id' => $new->id]);
        }
    }
}
