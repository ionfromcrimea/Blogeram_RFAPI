<?php

namespace App\Http\Controllers;

use App\Bloger;
use App\Http\Resources\NewsIdentifierResource;
use Illuminate\Http\Request;

class BlogersNewsRelationshipsController extends Controller

{
    public function index(Bloger $bloger)
    {
        return NewsIdentifierResource::collection($bloger->news);
    }
}
