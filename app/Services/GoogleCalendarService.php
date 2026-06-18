<?php

namespace App\Services;

use App\Models\GoogleToken;
use App\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\ConferenceSolutionKey;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\EventDateTime;

class GoogleCalendarService
{
    /**
     * Build an authenticated Google Client for the given faculty user.
     * Auto-refreshes the token if expired.
     */
    public function getClient(User $faculty): ?Client
    {
        /** @var GoogleToken|null $tokenRecord */
        $tokenRecord = GoogleToken::where('user_id', $faculty->id)->first();
        if (!$tokenRecord) return null;

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $accessToken = [
            'access_token'  => $tokenRecord->access_token,
            'refresh_token' => $tokenRecord->refresh_token,
            'token_type'    => $tokenRecord->token_type ?? 'Bearer',
            'expires_in'    => 3600,
            'created'       => $tokenRecord->expires_at
                ? ($tokenRecord->expires_at->timestamp - 3600)
                : now()->timestamp,
        ];

        $client->setAccessToken($accessToken);

        // Auto-refresh if expired
        if ($client->isAccessTokenExpired()) {
            if ($tokenRecord->refresh_token) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($tokenRecord->refresh_token);
                if (!isset($newToken['error'])) {
                    $tokenRecord->update([
                        'access_token' => $newToken['access_token'],
                        'expires_at'   => now()->addSeconds($newToken['expires_in'] ?? 3600),
                    ]);
                    $client->setAccessToken($newToken);
                }
            }
        }

        return $client;
    }

    /**
     * Create a Google Calendar event with a Meet link.
     *
     * @param  User    $faculty
     * @param  array   $data         ['title', 'description', 'start_at', 'end_at']
     * @param  array   $attendees    [['email' => .., 'name' => ..], ...]
     * @return array|null            ['google_event_id', 'meet_link']
     */
    public function createMeeting(User $faculty, array $data, array $attendees): ?array
    {
        $client = $this->getClient($faculty);
        if (!$client) return null;

        $service = new Calendar($client);

        // Build attendees list
        $eventAttendees = array_map(function ($a) {
            $attendee = new EventAttendee();
            $attendee->setEmail($a['email']);
            if (!empty($a['name'])) $attendee->setDisplayName($a['name']);
            return $attendee;
        }, $attendees);

        // Build the event
        $event = new Event([
            'summary'     => $data['title'],
            'description' => $data['description'] ?? '',
            'start'       => new EventDateTime([
                'dateTime' => $data['start_at']->toRfc3339String(),
                'timeZone' => config('app.timezone', 'Asia/Kolkata'),
            ]),
            'end' => new EventDateTime([
                'dateTime' => $data['end_at']->toRfc3339String(),
                'timeZone' => config('app.timezone', 'Asia/Kolkata'),
            ]),
            'attendees'      => $eventAttendees,
            'guestsCanSeeOtherGuests' => false, // Privacy: attendees can't see each other
            'conferenceData' => new ConferenceData([
                'createRequest' => new CreateConferenceRequest([
                    'requestId'             => uniqid(),
                    'conferenceSolutionKey' => new ConferenceSolutionKey(['type' => 'hangoutsMeet']),
                ]),
            ]),
            // Restrict to invited guests only
            'visibility' => 'private',
            'guestsCanInviteOthers' => false,
            'guestsCanModify'       => false,
        ]);

        try {
            $createdEvent = $service->events->insert('primary', $event, [
                'conferenceDataVersion' => 1,
                'sendUpdates'           => 'all', // Google also sends calendar invites
            ]);

            $meetLink = $createdEvent->getConferenceData()?->getEntryPoints();
            $link = null;
            if ($meetLink) {
                foreach ($meetLink as $ep) {
                    if ($ep->getEntryPointType() === 'video') {
                        $link = $ep->getUri();
                        break;
                    }
                }
            }

            return [
                'google_event_id' => $createdEvent->getId(),
                'meet_link'       => $link ?? $createdEvent->getHangoutLink(),
            ];
        } catch (\Exception $e) {
            \Log::error('Google Meet creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancel (delete) a Google Calendar event.
     */
    public function cancelMeeting(User $faculty, string $googleEventId): bool
    {
        $client = $this->getClient($faculty);
        if (!$client) return false;

        try {
            $service = new Calendar($client);
            $service->events->delete('primary', $googleEventId, ['sendUpdates' => 'all']);
            return true;
        } catch (\Exception $e) {
            \Log::error('Google Meet cancel failed: ' . $e->getMessage());
            return false;
        }
    }
}
