<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ActivityLogger — satu-satunya pintu untuk menulis activity log.
 *
 * Cara pakai:
 *   ActivityLogger::log('transaction.created', 'Transaksi TRX-20260314-00001 berhasil dibuat', $transaction);
 *   ActivityLogger::error('Gagal proses transaksi', $exception, ['cart' => $items]);
 */
class ActivityLogger
{
    /**
     * Tulis log aktivitas umum.
     *
     * @param  string       $event        Identifier event, e.g. 'transaction.created'
     * @param  string       $description  Kalimat human-readable
     * @param  object|null  $subject      Model Eloquent yang dioperasikan (opsional)
     * @param  array        $properties   Data konteks tambahan
     * @param  string       $level        info | warning | error | critical
     */
    public static function log(
        string $event,
        string $description,
        ?object $subject = null,
        array $properties = [],
        string $level = 'info'
    ): void {
        try {
            $request = request();
            $user    = auth()->user();

            ActivityLog::create([
                'user_id'      => $user?->id,
                'user_name'    => $user?->name,
                'user_role'    => $user?->role,
                'event'        => $event,
                'level'        => $level,
                'description'  => $description,
                'url'          => $request ? substr($request->fullUrl(), 0, 1000) : null,
                'method'       => $request?->method(),
                'ip_address'   => $request?->ip(),
                'user_agent'   => $request ? substr($request->userAgent() ?? '', 0, 500) : null,
                'properties'   => $properties ?: null,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->getKey(),
            ]);

            // Juga tulis ke file log Laravel agar bisa dibaca via `tail -f`
            Log::channel('activity')->{$level}("[{$event}] {$description}", $properties);
        } catch (Throwable $e) {
            // Jangan sampai logger sendiri crash app
            Log::error('ActivityLogger failed: ' . $e->getMessage());
        }
    }

    /**
     * Tulis log error dengan detail exception.
     *
     * @param  string     $description  Konteks singkat apa yang sedang dikerjakan
     * @param  Throwable  $exception    Exception yang ditangkap
     * @param  array      $properties   Data konteks tambahan (payload, model id, dsb)
     * @param  string     $level        'error' | 'critical'
     */
    public static function error(
        string $description,
        Throwable $exception,
        array $properties = [],
        string $level = 'error'
    ): void {
        try {
            $request = request();
            $user    = auth()->user();

            // Potong trace agar tidak overflow DB
            $trace = substr($exception->getTraceAsString(), 0, 3000);

            ActivityLog::create([
                'user_id'           => $user?->id,
                'user_name'         => $user?->name,
                'user_role'         => $user?->role,
                'event'             => $level,  // 'error' atau 'critical'
                'level'             => $level,
                'description'       => $description,
                'url'               => $request ? substr($request->fullUrl(), 0, 1000) : null,
                'method'            => $request?->method(),
                'ip_address'        => $request?->ip(),
                'user_agent'        => $request ? substr($request->userAgent() ?? '', 0, 500) : null,
                'properties'        => $properties ?: null,
                'exception_message' => $exception->getMessage(),
                'exception_class'   => get_class($exception),
                'exception_trace'   => $trace,
                'file'              => $exception->getFile(),
                'line'              => $exception->getLine(),
            ]);

            Log::channel('activity')->{$level}(
                "[error] {$description} — " . get_class($exception) . ': ' . $exception->getMessage(),
                ['file' => $exception->getFile(), 'line' => $exception->getLine()] + $properties
            );
        } catch (Throwable $e) {
            Log::error('ActivityLogger::error failed: ' . $e->getMessage());
        }
    }

    // ─── Shorthand helpers ────────────────────────────────────────────────────

    public static function info(string $event, string $description, ?object $subject = null, array $props = []): void
    {
        self::log($event, $description, $subject, $props, 'info');
    }

    public static function warning(string $event, string $description, ?object $subject = null, array $props = []): void
    {
        self::log($event, $description, $subject, $props, 'warning');
    }

    public static function critical(string $description, Throwable $exception, array $props = []): void
    {
        self::error($description, $exception, $props, 'critical');
    }
}
