<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\GoogleToken;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Oauth2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    private function buildClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(Calendar::CALENDAR);
        $client->addScope(Oauth2::USERINFO_EMAIL);
        $client->setAccessType('offline');
        $client->setPrompt('consent select_account'); // force refresh token
        return $client;
    }

    /**
     * Redirect faculty to Google OAuth consent screen.
     */
    public function redirect()
    {
        $client = $this->buildClient();
        return redirect($client->createAuthUrl());
    }

    /**
     * Handle the OAuth callback — store tokens and redirect back to meetings.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('faculty.meetings.index')
                ->with('error', 'Google account linking was cancelled.');
        }

        $client = $this->buildClient();

        try {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            if (isset($token['error'])) {
                return redirect()->route('faculty.meetings.index')
                    ->with('error', 'Failed to link Google account: ' . $token['error_description']);
            }

            $client->setAccessToken($token);

            // Get the linked Google email
            $oauth2Service = new Oauth2($client);
            $userInfo = $oauth2Service->userinfo->get();

            GoogleToken::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'access_token'  => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? null,
                    'token_type'    => $token['token_type'] ?? 'Bearer',
                    'expires_at'    => now()->addSeconds($token['expires_in'] ?? 3600),
                    'google_email'  => $userInfo->email,
                ]
            );

            return redirect()->route('faculty.meetings.index')
                ->with('success', 'Google account linked successfully! You can now schedule Meet sessions.');

        } catch (\Exception $e) {
            return redirect()->route('faculty.meetings.index')
                ->with('error', 'Google linking failed: ' . $e->getMessage());
        }
    }

    /**
     * Unlink the Google account.
     */
    public function unlink()
    {
        GoogleToken::where('user_id', Auth::id())->delete();

        return redirect()->route('faculty.meetings.index')
            ->with('success', 'Google account unlinked.');
    }
}
