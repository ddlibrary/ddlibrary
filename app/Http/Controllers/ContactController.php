<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactPage;
use App\Models\Contact;
use App\Models\Setting;
use App\Rules\RecaptchaRule;
use App\Traits\SitewidePageViewTrait;
use BladeView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContactController extends Controller
{
    use SitewidePageViewTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function index(): View
    {
        $this->middleware('admin');

        $records = Contact::orderByDesc('id')->paginate(10);

        return view('admin.contacts.contact_list', compact('records'));
    }

    public function read($id): RedirectResponse
    {
        $contact = Contact::find($id);
        if ($contact->isread == 0) {
            $contact->isread = 1;
            $contact->save();

            return back()->with('success', 'You marked the message as read!');
        } else {
            $contact->isread = 0;
            $contact->save();

            return back()->with('success', 'You marked the message as unread!');
        }
    }

    public function delete($id): RedirectResponse
    {
        $contact = Contact::find($id);
        $contact->delete();

        return back()->with('error', 'You deleted the record!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function create(Request $request): View
    {
        $this->pageView($request, 'Contact us');

        if ($request->user()) {
            $user = $request->user();

            if ($user->email) {
                return view('contacts.contacts_view', [
                    'email' => $user->email,
                    'fullname' => $user->profile->first_name.' '.$user->profile->last_name,
                ]);
            }
        }

        //setting the search session empty
        DDLClearSession();

        return view('contacts.contacts_view');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Application|Redirector|RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {

        //Saving contact info to the database
        $contact = new Contact;
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');

        $contact->save();

        if (config('mail.send_email') == 'yes') {
            Mail::to(Setting::find(1)->website_email)->send(new ContactPage($contact));
        }

        return redirect('/contact-us')->with('success', __('We received your message and will contact you back soon!'));
    }
}
