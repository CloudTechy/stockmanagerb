<?php

namespace App\Services;

use App\Helper;
use App\IdempotencyRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IdempotencyService
{
    public function begin(Request $request, string $scope, int $userId): array
    {
        $idempotencyKey = $this->extractKey($request);
        if (empty($idempotencyKey)) {
            return [null, null];
        }

        $requestHash = $this->hashPayload($request);

        try {
            $record = IdempotencyRequest::create([
                'user_id' => $userId,
                'scope' => $scope,
                'idempotency_key' => $idempotencyKey,
                'request_hash' => $requestHash,
                'status' => 'processing',
            ]);
            return [$record, null];
        } catch (QueryException $exception) {
            if (!$this->isDuplicateConstraintViolation($exception)) {
                throw $exception;
            }

            $record = IdempotencyRequest::where([
                'user_id' => $userId,
                'scope' => $scope,
                'idempotency_key' => $idempotencyKey,
            ])->lockForUpdate()->first();

            if (empty($record)) {
                throw $exception;
            }

            if ($record->request_hash !== $requestHash) {
                return [null, Helper::invalidRequest('Idempotency key was already used with a different payload', 'Conflict', 409)];
            }

            if ($record->status === 'completed' && !empty($record->response_body)) {
                return [null, response($record->response_body, (int) $record->status_code)->header('Content-Type', 'application/json')];
            }

            return [null, Helper::invalidRequest('Idempotent request is already being processed', 'Conflict', 409)];
        }
    }

    public function complete(IdempotencyRequest $record, $response): void
    {
        $record->update([
            'status' => 'completed',
            'status_code' => $response->getStatusCode(),
            'response_body' => $response->getContent(),
            'processed_at' => now(),
        ]);
    }

    private function extractKey(Request $request): ?string
    {
        $key = $request->header('Idempotency-Key');
        if (empty($key)) {
            $key = $request->header('X-Idempotency-Key');
        }
        if (empty($key)) {
            $key = $request->input('idempotency_key');
        }
        if (empty($key)) {
            $key = $request->input('idempotencyKey');
        }

        return is_string($key) ? trim($key) : null;
    }

    private function hashPayload(Request $request): string
    {
        $payload = $request->except(['idempotency_key', 'idempotencyKey']);
        return hash('sha256', json_encode(Arr::sortRecursive($payload)));
    }

    private function isDuplicateConstraintViolation(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        return in_array($sqlState, ['23000', '23505'], true);
    }
}
