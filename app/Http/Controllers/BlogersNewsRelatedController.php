<?php

namespace App\Http\Controllers;

use App\Bloger;
use App\Http\Resources\NewsCollection;
use Illuminate\Http\Request;

class BlogersNewsRelatedController extends Controller
{
    public function index(Bloger $bloger)
    {
        return new NewsCollection($bloger->news);
    }
}
