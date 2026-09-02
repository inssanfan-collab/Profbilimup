<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use SimpleXMLElement;

/**
 * Тонкая обёртка над протоколом внешнего сервера видеоконференций (checksum-подписанный
 * XML-based API). Секретный ключ (config('services.video_conference.secret')) участвует
 * только в вычислении checksum и никогда не попадает в исходящий URL или логи.
 */
class VideoConferenceService
{
    private string $baseUrl;

    private string $secret;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.video_conference.url'), '/').'/';
        $this->secret = (string) config('services.video_conference.secret');
    }

    /**
     * @return array{joinUrl: string}
     */
    public function createMeeting(string $meetingId, string $name, string $moderatorPassword, string $attendeePassword): array
    {
        $params = [
            'meetingID' => $meetingId,
            'name' => $name,
            'moderatorPW' => $moderatorPassword,
            'attendeePW' => $attendeePassword,
            'record' => 'false',
        ];

        $response = $this->request('create', $params);

        if (($response['returncode'] ?? null) !== 'SUCCESS') {
            Log::warning('video_conference.create_failed', ['meetingID' => $meetingId, 'message' => $response['message'] ?? null]);

            throw new RuntimeException('Не удалось создать видеовстречу на сервере видеосвязи.');
        }

        return $response;
    }

    public function joinUrl(string $meetingId, string $password, string $fullName): string
    {
        return $this->buildUrl('join', [
            'meetingID' => $meetingId,
            'password' => $password,
            'fullName' => $fullName,
            'redirect' => 'true',
        ]);
    }

    public function endMeeting(string $meetingId, string $moderatorPassword): bool
    {
        $response = $this->request('end', [
            'meetingID' => $meetingId,
            'password' => $moderatorPassword,
        ]);

        return ($response['returncode'] ?? null) === 'SUCCESS';
    }

    public function isRunning(string $meetingId): bool
    {
        $response = $this->request('isMeetingRunning', ['meetingID' => $meetingId]);

        return ($response['returncode'] ?? null) === 'SUCCESS' && ($response['running'] ?? 'false') === 'true';
    }

    private function request(string $apiCallName, array $params): array
    {
        $response = Http::timeout(10)->get($this->buildUrl($apiCallName, $params));

        return $this->parseXml($response->body());
    }

    private function buildUrl(string $apiCallName, array $params): string
    {
        $queryString = http_build_query($params);
        $checksum = hash('sha256', $apiCallName.$queryString.$this->secret);

        return "{$this->baseUrl}api/{$apiCallName}?{$queryString}&checksum={$checksum}";
    }

    private function parseXml(string $xml): array
    {
        $previous = libxml_use_internal_errors(true);
        $element = simplexml_load_string($xml);
        libxml_use_internal_errors($previous);

        if ($element === false) {
            Log::warning('video_conference.invalid_response');

            throw new RuntimeException('Сервер видеосвязи вернул некорректный ответ.');
        }

        return json_decode(json_encode($element), true) ?: [];
    }
}
