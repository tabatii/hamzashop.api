<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function join(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter|max:100'
        ]);
        $newsletter = new Newsletter;
        $newsletter->email = $request->email;
        $newsletter->save();
        return response()->json();
    }
}
