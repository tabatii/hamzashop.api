<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(ContactRequest $request)
    {
        Mail::to(env('MAIL_USERNAME'))->send(new ContactMail($request->validated()));
    }
}
