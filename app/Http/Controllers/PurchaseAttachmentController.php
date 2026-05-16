<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PurchaseAttachmentController extends Controller
{
    /**
     * Delete an attachment from a purchase.
     * Expects: purchase_id, path
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'purchase_id' => ['required', 'integer', 'exists:purchases,id'],
        ]);

        $purchase = Purchase::findOrFail($data['purchase_id']);

        // Authorization: allow if user can update the purchase (policy),
        // or if they are the uploader of the specific file, or if they are superadmin
        $user = $request->user();
        $isSuper = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
        $canUpdatePurchase = $user && method_exists($user, 'can') ? $user->can('update', $purchase) : false;


        // Path can come from a dedicated field or from the submit button's name/value
        $path = $request->input('path');
        if (! is_string($path) || $path === '') {
            // Try to read from request data where the button sent the value under key 'path'
            $path = $request->request->get('path');
        }
        if (! is_string($path) || $path === '') {
            Log::warning('[PurchaseAttachmentController@destroy] Missing path', [
                'user_id' => optional($user)->id,
                'purchase_id' => $purchase->id,
            ]);
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => 'Path requis'], 422);
            }
            abort(422, 'Path requis');
        }
        // Helper to normalize a value (path or URL) to a disk-relative path like 'purchases/attachments/...'
        $startsWith = function (string $haystack, string $needle): bool {
            return strncmp($haystack, $needle, strlen($needle)) === 0;
        };
        $normalize = function ($value) use ($startsWith) {
            if (! is_string($value) || $value === '') {
                return null;
            }
            // If value is a URL, keep only the path part
            $pathPart = parse_url($value, PHP_URL_PATH) ?? $value;
            $pathPart = ltrim($pathPart, '/');
            // If path starts with 'storage/', strip that prefix to get the disk-relative path
            if ($startsWith($pathPart, 'storage/')) {
                return substr($pathPart, strlen('storage/')) ?: null;
            }
            // If path contains '/storage/', extract the part after it
            $pos = strpos($pathPart, '/storage/');
            if ($pos !== false) {
                $after = substr($pathPart, $pos + 9);
                return $after !== '' ? $after : null;
            }
            return $pathPart !== '' ? $pathPart : null;
        };
        $normalizedPath = $normalize($path);
        Log::info('[PurchaseAttachmentController@destroy] Request', [
            'user_id' => optional($user)->id,
            'purchase_id' => $purchase->id,
            'raw_path' => $path,
            'normalized_path' => $normalizedPath,
        ]);

        // Determine whether the current user is allowed to delete this specific file
        $canDelete = false;
        $isOwnerOfPurchase = $user && (int) ($purchase->user_id ?? 0) === (int) $user->id;

        $attachments = (array) ($purchase->attachments ?? []);
        foreach ($attachments as $entry) {
            $entryPath = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryUrl = is_string($entry) ? null : ($entry['url'] ?? null);
            $matches = false;
            foreach ([$entryPath, $entryUrl] as $cand) {
                $candNorm = $normalize($cand);
                if ($candNorm && $normalizedPath && $candNorm === $normalizedPath) { $matches = true; break; }
            }
            if (! $matches) { continue; }

            // Superadmin and users authorized by policy can delete
            if ($isSuper || $canUpdatePurchase || $isOwnerOfPurchase) { $canDelete = true; break; }

            // Preferred: explicit owner metadata
            $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
            if ($user && $uploader && (int) $user->id === (int) $uploader) { $canDelete = true; break; }

            // Fallback ONLY if there is no owner metadata on this entry
            if (! $uploader) {
                $basename = basename($entryPath);
                // Accept historical pattern: uid{userId}__...
                if (preg_match('/^uid(\d+)__*/i', $basename, $m)) {
                    $ownerId = (int) ($m[1] ?? 0);
                    if ($ownerId && $user && (int) $user->id === $ownerId) { $canDelete = true; break; }
                }
            }

            // If we found the entry but ownership didn't match, stop checking
            break;
        }

        // If not found in attachments, check photos
        if (! $canDelete) {
            $photos = (array) ($purchase->photos ?? []);
            foreach ($photos as $entry) {
                $entryPath = is_string($entry) ? $entry : ($entry['path'] ?? ($entry[0] ?? null));
                $entryUrl = is_string($entry) ? null : ($entry['url'] ?? null);
                $matches = false;
                foreach ([$entryPath, $entryUrl] as $cand) {
                    $candNorm = $normalize($cand);
                    if ($candNorm && $normalizedPath && $candNorm === $normalizedPath) { $matches = true; break; }
                }
                if ($matches) {
                    if ($isSuper || $canUpdatePurchase || $isOwnerOfPurchase) { $canDelete = true; break; }

                    $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
                    if ($user && $uploader && (int) $user->id === (int) $uploader) { $canDelete = true; break; }

                    if (! $uploader) {
                        $basename = basename($entryPath);
                        if (preg_match('/^uid(\d+)__*/i', $basename, $m)) {
                            $ownerId = (int) ($m[1] ?? 0);
                            if ($ownerId && $user && (int) $user->id === $ownerId) { $canDelete = true; break; }
                        }
                    }
                    break;
                }
            }
        }

        if (! $canDelete) {
            Log::warning('[PurchaseAttachmentController@destroy] Not authorized', [
                'user_id' => optional($user)->id,
                'purchase_user_id' => $purchase->user_id,
                'is_super' => $isSuper,
                'can_update' => $canUpdatePurchase,
                'is_owner' => $isOwnerOfPurchase,
            ]);
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
            abort(403);
        }

        // Remove file from storage if exists
        try {
            if ($normalizedPath && Storage::disk('public')->exists($normalizedPath)) {
                Storage::disk('public')->delete($normalizedPath);
                Log::info('[PurchaseAttachmentController@destroy] Deleted file from storage', ['path' => $normalizedPath]);
            }
        } catch (\Throwable $e) {
            Log::error('[PurchaseAttachmentController@destroy] Storage delete error', ['error' => $e->getMessage(), 'path' => $normalizedPath]);
            // ignore storage delete errors, proceed to remove reference
        }

        // Update attachments and photos arrays on the model
        $changed = false;
        $attachments = (array) ($purchase->attachments ?? []);
        $filtered = collect($attachments)->filter(function ($entry) use ($normalizedPath, $normalize, &$changed, $user, $isSuper, $canUpdatePurchase, $isOwnerOfPurchase) {
            $entryPath = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryUrl = is_string($entry) ? null : ($entry['url'] ?? null);
            $matches = false;
            foreach ([$entryPath, $entryUrl] as $cand) {
                $candNorm = $normalize($cand);
                if ($candNorm && $normalizedPath && $candNorm === $normalizedPath) { $matches = true; break; }
            }
            if (! $matches) { return true; }

            // For targeted entry: check uploader ownership or admin
            $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
            if ($isSuper || $canUpdatePurchase || $isOwnerOfPurchase) {
                $changed = true;
                return false; // allow deletion by superadmin
            }
            if ($user && $uploader && (int)$user->id === (int)$uploader) {
                $changed = true;
                return false; // allow deletion by original uploader
            }

            // Fallback only when no owner metadata exists
            if (! $uploader) {
                $basename = basename($entryPath);
                if (preg_match('/^uid(\d+)__*/i', $basename, $m)) {
                    $ownerId = (int) ($m[1] ?? 0);
                    if ($ownerId && $user && (int) $user->id === $ownerId) {
                        $changed = true;
                        return false;
                    }
                }
            }

            // Not allowed to delete this entry — keep it
            return true;
        })->values()->all();

        if ($changed) {
            $purchase->attachments = $filtered;
            $purchase->save();
            Log::info('[PurchaseAttachmentController@destroy] Attachment reference removed and saved');
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => 'Document supprimé'], 200);
            }
            return back()->with('status', 'Document supprimé');
        }

        // Also try photos
        $photos = (array) ($purchase->photos ?? []);
        $changedPhoto = false;
        $filteredPhotos = collect($photos)->filter(function ($entry) use ($normalizedPath, $normalize, &$changedPhoto, $user, $isSuper, $canUpdatePurchase, $isOwnerOfPurchase) {
            $entryPath = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryUrl = is_string($entry) ? null : ($entry['url'] ?? null);
            $matches = false;
            foreach ([$entryPath, $entryUrl] as $cand) {
                $candNorm = $normalize($cand);
                if ($candNorm && $normalizedPath && $candNorm === $normalizedPath) { $matches = true; break; }
            }
            if (! $matches) { return true; }

            $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
            if ($isSuper || $canUpdatePurchase || $isOwnerOfPurchase) {
                $changedPhoto = true;
                return false;
            }
            if ($user && $uploader && (int)$user->id === (int)$uploader) {
                $changedPhoto = true;
                return false;
            }

            if (! $uploader) {
                $basename = basename($entryPath);
                if (preg_match('/^uid(\d+)__*/i', $basename, $m)) {
                    $ownerId = (int) ($m[1] ?? 0);
                    if ($ownerId && $user && (int) $user->id === $ownerId) {
                        $changedPhoto = true;
                        return false;
                    }
                }
            }

            return true;
        })->values()->all();

        if ($changedPhoto) {
            $purchase->photos = $filteredPhotos;
            $purchase->save();
            Log::info('[PurchaseAttachmentController@destroy] Photo reference removed and saved');
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => 'Photo supprimée'], 200);
            }
            return back()->with('status', 'Photo supprimée');
        }

        Log::warning('[PurchaseAttachmentController@destroy] No matching document/photo found', [
            'normalized_path' => $normalizedPath,
        ]);
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['message' => 'Aucun document trouvé'], 404);
        }
        return back()->with('status', 'Aucun document trouvé');
    }

    /**
     * Delete via signed GET URL to avoid nested form/JS issues in modals.
     * Route guarded by 'signed' and 'auth' middlewares.
     * Query: purchase_id, path
     */
    public function destroySigned(Request $request)
    {
        if (! URL::hasValidSignature($request)) {
            abort(403);
        }
        $request->validate([
            'purchase_id' => ['required', 'integer', 'exists:purchases,id'],
            'path' => ['required', 'string'],
        ]);
        $ajax = (bool) $request->boolean('ajax');

        // Reuse core logic by constructing a new Request with POST semantics
        $post = Request::create(
            url()->route('purchases.attachments.delete'),
            'POST',
            [
                'purchase_id' => (int) $request->query('purchase_id'),
                'path' => (string) $request->query('path'),
            ]
        );
        $post->setUserResolver(fn () => $request->user());
        app()->instance('request', $post);
        $response = $this->destroy($post);

        // Optional explicit redirect target to avoid mobile referer quirks
        $redirect = (string) $request->query('redirect', '');
        if ($ajax) {
            // Always return a JSON OK for AJAX link to prevent Livewire SPA redirects
            return response()->json(['ok' => true, 'status' => session('status', 'Document supprimé')]);
        }
        if ($redirect !== '') {
            // Allow only relative paths or same-host absolute URLs
            $isSafe = false;
            if (str_starts_with($redirect, '/')) {
                $isSafe = true;
            } else {
                $parsed = parse_url($redirect);
                $currentHost = $request->getHost();
                if (!empty($parsed['scheme']) && !empty($parsed['host']) && !empty($currentHost) && strcasecmp($parsed['host'], $currentHost) === 0) {
                    $isSafe = true;
                }
            }
            if ($isSafe) {
                return redirect()->to($redirect)->with('status', session('status', 'Document supprimé'));
            }
        }

        // If original request was a normal browser navigation (not AJAX), prefer redirect back
        if (!($request->ajax() || $request->expectsJson())) {
            return redirect()->back()->with('status', session('status', 'Document supprimé'));
        }
        return $response;
    }
}
