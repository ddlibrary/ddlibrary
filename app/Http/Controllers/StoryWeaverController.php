<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

namespace App\Http\Controllers;

use App\UserProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class StoryWeaverController extends Controller
{
    function storyWeaverConfirmation()
    {
        $email = false;
        if (auth()->user()->email) {
            $email = true;
        }
        $previous_url = URL::previous();
        session(['previous_url' => $previous_url]);
        return view('storyweaver.confirmation', compact('email'));
    }

    function storyWeaverAuth()
    {
        $storyweaver_url = config('constants.storyweaver_url');
        $secret = config('storyweaver.config.secret');

        if (! $storyweaver_url or ! $secret) {
            Log::info(
                'StoryWeaver URL or Org Token not properly configured.'
            );
            abort(500);
        }

        $user = auth()->user();
        $user_email = $user->email;
        if (! $user_email) {
            abort(405, __('You cannot access the Darkht-e Danesh StoryWeaver Library without a registered email.'));
        }
        $user_id = $user->id;

        $user = UserProfile::where('user_id', $user_id)->first();
        $first_name = $user->first_name;
        $last_name = $user->last_name;

        $language = Config::get('app.locale');
        $redirect_home = session()->pull('previous_url');
        if (! $redirect_home) {
            $redirect_home = URL::to('/');
        }

        $client = new Client();
        try {
            $response = $client->request(
                'POST', $storyweaver_url, [
                    'multipart' => [
                        [
                            'name'    => 'org_token',
                            'contents' => $secret
                        ],
                        [
                            'name' => 'firstname',
                            'contents' => $first_name
                        ],
                        [
                            'name' => 'lastname',
                            'contents' => $last_name
                        ],
                        [
                            'name' => 'email',
                            'contents' => $user_email
                        ],
                        [
                            'name' => 'language_preferences',
                            'contents' => $language
                        ],
                        [
                            'name' => 'locale_preferences',
                            'contents' => $language
                        ],
                        [
                            'name' => 'redirect_home',
                            'contents' => $redirect_home
                        ],
                    ]
                ]
            );
        } catch (ClientException $e) {
            if  ($e->getResponse()->getStatusCode() == 422) {
                Log::info(
                    $first_name . ' ' . $last_name . 'was unable to authenticate to StoryWeaver.
                    Email: ' . $user_email . ' and user id: ' . $user_id .'. Response: 422.'
                );
                abort(422, __('Something went wrong while redirecting you to StoryWeaver.'));
            }

        } catch (TransferException $e) {
            Log::info(
                $first_name . ' ' . $last_name . 'was unable to authenticate to StoryWeaver. 
                Server 500.'
            );
            abort(500);
        }

        $response_contents = json_decode($response->getBody());

        if (
            $response->getStatusCode() == 200
            && $response_contents->status == 'success'
            && $response_contents->redirect_url
        ) {
            return redirect()->away($response_contents->redirect_url);
        }

        return redirect(route('home'));
    }
}
