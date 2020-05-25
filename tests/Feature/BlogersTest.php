<?php

namespace Tests\Feature;

use App\Bloger;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BlogersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @@wat
     */
    public function it_returns_an_bloger_as_a_resource_object()
    {
        $bloger = factory(Bloger::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->getJson('/api/blogers/1', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $bloger->login,
                        'password' => $bloger->password,
                        'status' => $bloger->status,
                        'created_at' => $bloger->created_at->toJSON(),
                        'updated_at' => $bloger->updated_at->toJSON(),
                    ]
                ]
            ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_returns_all_blogers_as_a_collection_of_resource_objects()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $blogers = factory(Bloger::class, 3)->create();
        $this->get('/api/blogers', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
//                        'login' => '',
//                        'password' => $blogers->password,
//                        'status' => $blogers->status,
                        'created_at' => $blogers[0]->created_at->toJSON(),
                        'updated_at' => $blogers[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "blogers",
                    "attributes" => [
//                        'login' => $blogers->login,
//                        'password' => $blogers->password,
//                        'status' => $blogers->status,
                        'created_at' => $blogers[1]->created_at->toJSON(),
                        'updated_at' => $blogers[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "blogers",
                    "attributes" => [
//                        'login' => $blogers->login,
//                        'password' => $blogers->password,
//                        'status' => $blogers->status,
                        'created_at' => $blogers[2]->created_at->toJSON(),
                        'updated_at' => $blogers[2]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_create_an_bloger_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->postJson('/api/blogers', [
            'data' => [
                'type' => 'blogers',
                'attributes' => [
                    'login' => 'john',
                    'password' => 'pass',
                    'status' => "stat",
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'john',
                        'password' => 'pass',
                        'status' => "stat",
                        'created_at' => now()->setMilliseconds(0)->toJSON(),
                        'updated_at' => now()->setMilliseconds(0)->toJSON(),
                    ]
                ]
            ])->assertHeader('Location', url('/api/blogers/1'));
        $this->assertDatabaseHas('blogers', [
            'id' => 1,
            'login' => 'john',
            'password' => 'pass',
            'status' => "stat",
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_update_an_author_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $bloger = factory(Bloger::class)->create();
        $creationTimestamp = now();
        sleep(1);
        $this->patchJson('/api/blogers/1', [
            'data' => [
                'id' => '1',
                'type' => 'blogers',
                'attributes' => [
                    'status' => "stat33",
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            'data' => [
                'id' => '1',
                'type' => 'blogers',
                'attributes' => [
                    'status' => "stat33",
                    'created_at' => $creationTimestamp->setMilliseconds(0)->toJSON(),
                    'updated_at' => now()->setMilliseconds(0)->toJSON()
                    ,
                ],
            ]
        ]);
        $this->assertDatabaseHas('blogers', [
            'id' => 1,
            'status' => "stat33",
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_delete_an_author_through_a_delete_request()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $bloger = factory(Bloger::class)->create();
        $this->delete('/api/blogers/1', [], [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->assertStatus(204);
        $this->assertDatabaseMissing('blogers', [
            'id' => 1,
            'login' => $bloger->login,
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_validates_that_the_type_member_is_given_when_creating_an_bloger()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->postJson('/api/blogers', [
            'data' => [
                'type' => '',
                'attributes' => [
                    'login' => 'logiin',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Error',
                        'details' => 'The data.type field is required.',
                        'source' => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);
        $this->assertDatabaseMissing('blogers', [
            'id' => 1,
            'login' => 'logiin',
        ]);
    }

    /*
     ********** testing QueryBuilder *********
     *
     * // GET /blogers?filter[login]=john&filter[status]=1
     * // GET /blogers?filter[login]=john,doe
     * // GET /blogers?filter[scopeStatus]=3
     * // GET /blogers?filter[scopeStatus]=3,4
     * // GET /blogers?sort=login,-status
     * // GET /blogers[size]=5&page[number]=1
     *
     * /

    /**
     * @test
     * @@wat
     */
    public function it_can_sort_blogers_by_name_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $blogers = collect([
            'Bertram',
            'Claus',
            'Anna',
        ])->map(function ($login) {
            return factory(Bloger::class)->create([
                'login' => $login,
                'password' => "pass",
                'status' => 'stat'
            ]);
        });
        $this->get('/api/blogers?sort=login', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '3',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Anna',
                        'password' => 'pass',
                        'status' => 'stat',
                        'created_at' => $blogers[2]->created_at->toJSON(),
                        'updated_at' => $blogers[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Bertram',
                        'created_at' => $blogers[0]->created_at->toJSON(),
                        'updated_at' => $blogers[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Claus',
                        'created_at' => $blogers[1]->created_at->toJSON(),
                        'updated_at' => $blogers[1]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_sort_blogers_by_name_in_descending_order_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $blogers = collect([
            'Bertram',
            'Claus',
            'Anna',
        ])->map(function ($login) {
            return factory(Bloger::class)->create([
                'login' => $login,
                'password' => "pass",
                'status' => 'stat'
            ]);
        });
        $this->get('/api/blogers?sort=-login', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '2',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Claus',
                        'created_at' => $blogers[1]->created_at->toJSON(),
                        'updated_at' => $blogers[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Bertram',
                        'created_at' => $blogers[0]->created_at->toJSON(),
                        'updated_at' => $blogers[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Anna',
                        'password' => 'pass',
                        'status' => 'stat',
                        'created_at' => $blogers[2]->created_at->toJSON(),
                        'updated_at' => $blogers[2]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_sort_blogers_by_multiple_attributes_in_descending_order_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $blogers = collect([
            'Bertram',
            'Claus',
            'Anna',
        ])->map(function ($login) {
            if ($login === 'Bertram') {
                return factory(Bloger::class)->create([
                    'login' => $login,
                    'password' => "pass",
                    'status' => 'stat',
                    'created_at' => now()->addSeconds(3),
                ]);
            }
            return factory(Bloger::class)->create([
                'login' => $login,
                'password' => "pass",
                'status' => 'stat',
            ]);
        });
        $this->get('/api/blogers?sort=-created_at,login', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Bertram',
                        'created_at' => $blogers[0]->created_at->toJSON(),
                        'updated_at' => $blogers[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Anna',
                        'password' => 'pass',
                        'status' => 'stat',
                        'created_at' => $blogers[2]->created_at->toJSON(),
                        'updated_at' => $blogers[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => 'Claus',
                        'created_at' => $blogers[1]->created_at->toJSON(),
                        'updated_at' => $blogers[1]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     * @@wat
     */
    public function it_can_paginate_blogers_through_a_page_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $blogers = factory(Bloger::class, 10)->create();
        $this->get('/api/blogers?page[size]=5&page[number]=1', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $blogers[0]->login,
                        'created_at' => $blogers[0]->created_at->toJSON(),
                        'updated_at' => $blogers[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $blogers[1]->login,
                        'created_at' => $blogers[1]->created_at->toJSON(),
                        'updated_at' => $blogers[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $blogers[2]->login,
                        'created_at' => $blogers[2]->created_at->toJSON(),
                        'updated_at' => $blogers[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '4',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $blogers[3]->login,
                        'created_at' => $blogers[3]->created_at->toJSON(),
                        'updated_at' => $blogers[3]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '5',
                    "type" => "blogers",
                    "attributes" => [
                        'login' => $blogers[4]->login,
                        'created_at' => $blogers[4]->created_at->toJSON(),
                        'updated_at' => $blogers[4]->updated_at->toJSON(),
                    ]
                ],
            ],
            'links' => [
                'first' => route('blogers.index', ['page[size]' => 5, 'page[number]' => 1]),
                'last' => route('blogers.index', ['page[size]' => 5, 'page[number]' => 2]),
                'prev' => null,
                'next' => route('blogers.index', ['page[size]' => 5, 'page[number]' => 2]),
            ]
        ]);
    }
}
