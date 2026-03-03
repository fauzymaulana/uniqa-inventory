/**
 * Debt Offline Manager
 * IndexedDB-backed offline queue for debt records and payments.
 * Auto-syncs when online, shows pending items in the UI.
 */

const DebtOffline = (() => {
    const DB_NAME    = 'uniqa-debt-offline';
    const DB_VERSION = 1;
    const STORE_DEBTS    = 'pending_debts';
    const STORE_PAYMENTS = 'pending_payments';

    let db = null;

    // ── Open / Init IndexedDB ──────────────────────────────────────
    function openDB() {
        return new Promise((resolve, reject) => {
            if (db) return resolve(db);
            const req = indexedDB.open(DB_NAME, DB_VERSION);
            req.onupgradeneeded = (e) => {
                const d = e.target.result;
                if (!d.objectStoreNames.contains(STORE_DEBTS)) {
                    d.createObjectStore(STORE_DEBTS, { keyPath: 'local_id', autoIncrement: true });
                }
                if (!d.objectStoreNames.contains(STORE_PAYMENTS)) {
                    d.createObjectStore(STORE_PAYMENTS, { keyPath: 'local_id', autoIncrement: true });
                }
            };
            req.onsuccess = (e) => { db = e.target.result; resolve(db); };
            req.onerror   = (e) => reject(e.target.error);
        });
    }

    // ── Generic helpers ────────────────────────────────────────────
    async function addRecord(store, data) {
        const d = await openDB();
        return new Promise((resolve, reject) => {
            const tx = d.transaction(store, 'readwrite');
            const req = tx.objectStore(store).add({ ...data, queued_at: new Date().toISOString() });
            req.onsuccess = () => resolve(req.result);
            req.onerror   = () => reject(req.error);
        });
    }

    async function getAllRecords(store) {
        const d = await openDB();
        return new Promise((resolve, reject) => {
            const tx  = d.transaction(store, 'readonly');
            const req = tx.objectStore(store).getAll();
            req.onsuccess = () => resolve(req.result);
            req.onerror   = () => reject(req.error);
        });
    }

    async function deleteRecord(store, key) {
        const d = await openDB();
        return new Promise((resolve, reject) => {
            const tx  = d.transaction(store, 'readwrite');
            const req = tx.objectStore(store).delete(key);
            req.onsuccess = () => resolve();
            req.onerror   = () => reject(req.error);
        });
    }

    // ── Public API ─────────────────────────────────────────────────

    /** Save a debt form to offline queue */
    async function saveDebt(formData, csrfToken) {
        const record = {
            debtor_type:    formData.get('debtor_type'),
            debtor_id:      formData.get('debtor_id') || null,
            debtor_name:    formData.get('debtor_name') || null,
            debtor_phone:   formData.get('debtor_phone') || null,
            debtor_address: formData.get('debtor_address') || null,
            amount:         formData.get('amount'),
            due_date:       formData.get('due_date'),
            description:    formData.get('description') || null,
            csrf:           csrfToken,
        };
        const id = await addRecord(STORE_DEBTS, record);
        return id;
    }

    /** Save a payment (cicilan) to offline queue */
    async function savePayment(debtId, formData, csrfToken, debtorName) {
        const record = {
            debt_id:      debtId,
            debtor_name:  debtorName || null,
            pay_amount:   formData.get('pay_amount'),
            pay_note:     formData.get('pay_note') || null,
            csrf:         csrfToken,
        };
        const id = await addRecord(STORE_PAYMENTS, record);
        return id;
    }

    /** Get all pending debts */
    async function getPendingDebts() {
        return getAllRecords(STORE_DEBTS);
    }

    /** Get all pending payments */
    async function getPendingPayments() {
        return getAllRecords(STORE_PAYMENTS);
    }

    /** Try to sync one pending debt to server */
    async function syncDebt(record, storeUrl) {
        const fd = new FormData();
        fd.append('_token',         record.csrf);
        fd.append('debtor_type',    record.debtor_type);
        if (record.debtor_id)      fd.append('debtor_id',      record.debtor_id);
        if (record.debtor_name)    fd.append('debtor_name',    record.debtor_name);
        if (record.debtor_phone)   fd.append('debtor_phone',   record.debtor_phone);
        if (record.debtor_address) fd.append('debtor_address', record.debtor_address);
        fd.append('amount',        record.amount);
        fd.append('due_date',      record.due_date);
        if (record.description)    fd.append('description',    record.description);

        const res = await fetch(storeUrl, { method: 'POST', body: fd });
        if (res.ok || res.redirected) {
            await deleteRecord(STORE_DEBTS, record.local_id);
            return true;
        }
        return false;
    }

    /** Try to sync one pending payment to server */
    async function syncPayment(record, baseUrl) {
        const url = `${baseUrl}/${record.debt_id}/pay-partial`;
        const fd  = new FormData();
        fd.append('_token',     record.csrf);
        fd.append('pay_amount', record.pay_amount);
        if (record.pay_note) fd.append('pay_note', record.pay_note);

        const res = await fetch(url, { method: 'POST', body: fd });
        if (res.ok || res.redirected) {
            await deleteRecord(STORE_PAYMENTS, record.local_id);
            return true;
        }
        return false;
    }

    /** Sync all pending records. Returns { debts: n, payments: n } synced */
    async function syncAll(config) {
        if (!navigator.onLine) return { debts: 0, payments: 0 };

        const pendingDebts    = await getPendingDebts();
        const pendingPayments = await getPendingPayments();
        let syncedDebts = 0, syncedPayments = 0;

        for (const record of pendingDebts) {
            try {
                const ok = await syncDebt(record, config.storeUrl);
                if (ok) syncedDebts++;
            } catch (_) { /* skip, will retry next time */ }
        }

        for (const record of pendingPayments) {
            try {
                const ok = await syncPayment(record, config.paymentBaseUrl);
                if (ok) syncedPayments++;
            } catch (_) { /* skip */ }
        }

        return { debts: syncedDebts, payments: syncedPayments };
    }

    return { openDB, saveDebt, savePayment, getPendingDebts, getPendingPayments, syncAll, deleteRecord, STORE_DEBTS, STORE_PAYMENTS };
})();

// ── Auto-sync on online event ──────────────────────────────────────
window.addEventListener('online', async () => {
    if (window.DEBT_SYNC_CONFIG) {
        const result = await DebtOffline.syncAll(window.DEBT_SYNC_CONFIG);
        if (result.debts > 0 || result.payments > 0) {
            showDebtToast(
                `✅ Sinkronisasi berhasil! ${result.debts} hutang & ${result.payments} cicilan ter-upload.`,
                'success'
            );
            setTimeout(() => window.location.reload(), 1800);
        }
        // Refresh offline badge counts
        if (typeof renderOfflineBadge === 'function') renderOfflineBadge();
    }
});

// ── Background Sync via Service Worker ────────────────────────────
window.addEventListener('load', () => {
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        navigator.serviceWorker.addEventListener('message', async (e) => {
            if (e.data?.type === 'SYNC_DEBTS' && window.DEBT_SYNC_CONFIG) {
                await DebtOffline.syncAll(window.DEBT_SYNC_CONFIG);
            }
        });
    }
});

// ── Toast helper ──────────────────────────────────────────────────
function showDebtToast(message, type = 'info') {
    let container = document.getElementById('debt-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'debt-toast-container';
        container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
        document.body.appendChild(container);
    }
    const colors = { success: '#198754', danger: '#dc3545', info: '#0dcaf0', warning: '#fd7e14' };
    const toast  = document.createElement('div');
    toast.style.cssText = `background:${colors[type]||colors.info};color:white;padding:12px 18px;border-radius:8px;
        box-shadow:0 4px 12px rgba(0,0,0,.2);font-size:.9rem;max-width:320px;animation:slideIn .3s ease;`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4500);
}
