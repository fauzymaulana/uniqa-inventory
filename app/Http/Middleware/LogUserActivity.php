<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogUserActivity — middleware yang mencatat semua request signifikan
 * dan menangkap exception yang tidak ter-handle ke activity log.
 *
 * Dipasang hanya pada route yang membutuhkan auth sehingga
 * tidak membanjiri log dengan request publik.
 */
class LogUserActivity
{
    /**
     * Method & path yang TIDAK perlu di-log (terlalu noisy, read-only).
     */
    private array $skipPatterns = [
        'GET:*/dashboard/daily-*',
        'GET:*/dashboard/monthly-data',
        'GET:*/dashboard/daily-data',
        'GET:*/expenses-daily-data',
        'GET:*/products/datatables',
        'GET:*/api/products/datatables',
        // Asset & internal
        'GET:*/storage/*',
        'GET:*/css/*',
        'GET:*/js/*',
    ];

    /**
     * Event mapping: method + route pattern → nama event yang mudah dibaca.
     */
    private array $eventMap = [
        'POST:/login'                    => 'login',
        'POST:/logout'                   => 'logout',
        'POST:*/store-transaction'        => 'transaction.created',
        'POST:*/sync-transactions'        => 'transaction.synced',
        'POST:*/sync-expenses'            => 'expense.synced',
        'POST:*/sync-debts'              => 'debt.synced',
        'POST:*/sync-debt-payments/*'    => 'debt.payment.synced',
        'POST:*/debts'                   => 'debt.created',
        'POST:*/debt/*/pay-one'          => 'debt.paid',
        'POST:*/debt/*/pay-partial'      => 'debt.paid',
        'POST:*/debts/*/pay-all'         => 'debt.paid',
        'POST:*/expenses'                => 'expense.created',
        'PUT:*/expenses/*'               => 'expense.updated',
        'DELETE:*/expenses/*'            => 'expense.deleted',
        'POST:*/products'                => 'product.created',
        'PUT:*/products/*'               => 'product.updated',
        'DELETE:*/products/*'            => 'product.deleted',
        'POST:*/products-import'         => 'product.imported',
        'POST:*/products/*/update-stock' => 'stock.adjusted',
        'POST:*/api/stock/adjust'        => 'stock.adjusted',
        'POST:*/cashiers'                => 'user.created',
        'PUT:*/cashiers/*'               => 'user.updated',
        'DELETE:*/cashiers/*'            => 'user.deleted',
        'POST:*/categories'              => 'category.created',
        'PUT:*/categories/*'             => 'category.updated',
        'DELETE:*/categories/*'          => 'category.deleted',
        'POST:*/profile'                 => 'profile.updated',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Skip jika request masuk daftar noisy
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $response = $next($request);

        // Hanya log mutasi (non-GET) atau error HTTP ≥ 400
        $isWrite     = !in_array($request->method(), ['GET', 'HEAD', 'OPTIONS']);
        $isErrorCode = $response->getStatusCode() >= 400;

        if ($isWrite || $isErrorCode) {
            $this->writeLog($request, $response->getStatusCode());
        }

        return $response;
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function writeLog(Request $request, int $statusCode): void
    {
        $event       = $this->resolveEvent($request);
        $level       = $statusCode >= 500 ? 'error'
                     : ($statusCode >= 400 ? 'warning'
                     : 'info');
        $description = $this->buildDescription($request, $statusCode, $event);

        ActivityLogger::log($event, $description, null, [
            'status_code' => $statusCode,
            'input'       => $this->safeInput($request),
        ], $level);
    }

    private function resolveEvent(Request $request): string
    {
        $key = $request->method() . ':' . $request->path();

        foreach ($this->eventMap as $pattern => $event) {
            [$method, $path] = explode(':', $pattern, 2);
            if ($request->method() === $method && fnmatch(ltrim($path, '/'), $request->path())) {
                return $event;
            }
        }

        // Fallback: method + path generik
        return strtolower($request->method()) . '.' . str_replace('/', '.', trim($request->path(), '/'));
    }

    private function buildDescription(Request $request, int $statusCode, string $event): string
    {
        $user   = auth()->user();
        $who    = $user ? "{$user->name} ({$user->role})" : 'Guest';
        $method = $request->method();
        $path   = $request->path();
        $status = $statusCode;

        $verb = match (true) {
            $method === 'POST'   => 'mengirim data ke',
            $method === 'PUT'    => 'memperbarui',
            $method === 'PATCH'  => 'memperbarui sebagian',
            $method === 'DELETE' => 'menghapus',
            default              => 'mengakses',
        };

        $friendly = $this->friendlyPath($path);

        $desc = "{$who} {$verb} {$friendly}";

        if ($statusCode >= 400) {
            $desc .= " — respons {$status}";
        }

        return $desc;
    }

    private function friendlyPath(string $path): string
    {
        $map = [
            'cashier/store-transaction' => 'Transaksi POS',
            'api/sync-transactions'     => 'Sinkronisasi Transaksi Offline',
            'api/sync-expenses'         => 'Sinkronisasi Pengeluaran Offline',
            'api/sync-debts'            => 'Sinkronisasi Hutang Offline',
            'api/sync-debt-payments'    => 'Sinkronisasi Cicilan Offline',
            'admin/products'            => 'Produk',
            'cashier/expenses'          => 'Pengeluaran (Kasir)',
            'admin/expenses'            => 'Pengeluaran (Admin)',
            'admin/debts'               => 'Catatan Hutang',
            'cashier/debts'             => 'Catatan Hutang (Kasir)',
        ];

        foreach ($map as $pattern => $label) {
            if (str_starts_with($path, $pattern)) {
                return $label;
            }
        }

        return "/{$path}";
    }

    private function shouldSkip(Request $request): bool
    {
        $key = $request->method() . ':/' . $request->path();

        foreach ($this->skipPatterns as $pattern) {
            if (fnmatch($pattern, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ambil input request, hapus field sensitif, batasi ukuran.
     */
    private function safeInput(Request $request): array
    {
        $sensitive = ['password', 'password_confirmation', 'current_password', 'token', 'secret'];
        $input     = $request->except($sensitive);

        // Batasi ukuran tiap value agar tidak overflow DB
        array_walk_recursive($input, function (&$val) {
            if (is_string($val) && strlen($val) > 500) {
                $val = substr($val, 0, 500) . '…[truncated]';
            }
        });

        return $input;
    }
}
