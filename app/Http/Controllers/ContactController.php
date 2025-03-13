<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;

class ContactController extends Controller
{
    public function showContactForm()
    {
        return Inertia::render('ContactPage');
    }

    public function contact(ContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create([
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'message' => $validated['message'],
        ]);

        Mail::raw("Contact message:\n\n" . $validated['message'], function ($message) use ($validated) {
            $message->to('support@skyurban.com')
                ->subject('New contact message ' . $validated['first_name'] . ' ' . $validated['last_name'])
                ->replyTo($validated['email']);
        });

        return back()->with('success', 'Message sent successfully.');
    }
}
