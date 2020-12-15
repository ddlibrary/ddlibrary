<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Setting;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Mail\ContactPage;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->middleware('admin');
        
        $records = Contact::orderBy('id','desc')->paginate(10);
        return view('admin.contacts.contact_list', compact('records'));
    }

    public function read($id)
    {
        $contact = Contact::find($id);
        if($contact->isread == 0){
            $contact->isread = 1;
            $contact->save();
            return back()->with('success', 'You marked the message as read!');
        }else{
            $contact->isread = 0;
            $contact->save();   
            return back()->with('success', 'You marked the message as unread!');
        }
    }

    public function delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        return back()->with('error', 'You deleted the record!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //setting the search session empty
        DDLClearSession();
        return view('contacts.contacts_view');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email',
            'subject'   => 'required',
            'message'   => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        //Saving contact info to the database
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');

        $contact->save();

        if(config('mail.send_email') == 'yes'){
            \Mail::to(Setting::find(1)->website_email)->send(new ContactPage($contact));
        }

        return redirect('/contact-us')->with('success', __('We received your message and will contact you back soon!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Contact  $contact
     * @return Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
