<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Setting;
use Illuminate\Http\Request;
use App\Mail\ContactPage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required',
            'subject'   => 'required',
            'message'   => 'required',
            'g-recaptcha-response' => 'sometimes|required|captcha'
        ]);

        //Saving contact info to the database
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');

        $contact->save();

        if(env('SEND_EMAIL') == 'yes'){
            \Mail::to(Setting::find(1)->website_email)->send(new ContactPage($contact));
        }

        return redirect('/contact-us')->with('success', 'We received your message and will contact you back soon!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
