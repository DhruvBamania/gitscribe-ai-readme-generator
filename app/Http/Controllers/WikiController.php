<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateWikiJob;

class WikiController extends Controller
{
    public function generate(Request $request)
    {
        $owner = $request->input('owner');
        $repo = $request->input('repo');
        
        GenerateWikiJob::dispatch(auth()->user(), $owner, $repo);
        
        return back()->with('success', "Wiki generation started in the background for {$owner}/{$repo}! You will see a Pull Request on GitHub once it is finished.");
    }
}
