<?php

namespace Tests\Feature;

use App\Services\VideoConferenceService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class VideoConferenceServiceTest extends TestCase
{
    public function test_create_meeting_returns_parsed_response_on_success(): void
    {
        Http::fake([
            '*/api/create*' => Http::response(
                '<response><returncode>SUCCESS</returncode><meetingID>abc</meetingID></response>',
                200
            ),
        ]);

        $result = app(VideoConferenceService::class)->createMeeting('abc', 'Test', 'modpw', 'attpw');

        $this->assertSame('SUCCESS', $result['returncode']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/create')
                && str_contains($request->url(), 'checksum=')
                && ! str_contains($request->url(), config('services.video_conference.secret'));
        });
    }

    public function test_create_meeting_throws_on_failure_response(): void
    {
        Http::fake([
            '*/api/create*' => Http::response(
                '<response><returncode>FAILED</returncode><message>Bad request</message></response>',
                200
            ),
        ]);

        $this->expectException(RuntimeException::class);

        app(VideoConferenceService::class)->createMeeting('abc', 'Test', 'modpw', 'attpw');
    }

    public function test_join_url_is_checksum_signed_and_never_contains_the_secret(): void
    {
        config(['services.video_conference.url' => 'https://video.example.test/bigbluebutton', 'services.video_conference.secret' => 'super-secret']);

        $url = app(VideoConferenceService::class)->joinUrl('meeting-1', 'pw', 'Иван Иванов');

        $this->assertStringContainsString('/api/join?', $url);
        $this->assertStringContainsString('checksum=', $url);
        $this->assertStringNotContainsString('super-secret', $url);
    }

    public function test_is_running_reflects_server_response(): void
    {
        Http::fake([
            '*/api/isMeetingRunning*' => Http::response(
                '<response><returncode>SUCCESS</returncode><running>true</running></response>',
                200
            ),
        ]);

        $this->assertTrue(app(VideoConferenceService::class)->isRunning('abc'));
    }
}
