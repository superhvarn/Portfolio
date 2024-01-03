<?php

namespace App\Http\Controllers;

use App\Providers\Github;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class GithubController extends BaseController
{
    public function index() {
        $accessToken = file_get_contents(env('PEM'));      
        $this->github = new Github();
        return $this->github->getRepos($accessToken);
    }
}

