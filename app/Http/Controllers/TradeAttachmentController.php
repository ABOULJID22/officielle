<?php

namespace App\Http\Controllers;

use App\Models\TradeOperation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class TradeAttachmentController extends Controller
{
    /**
     * Delete an attachment or photo from a trade operation.
     * Expects: trade_id, path
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'trade_id' => ['required', 'integer', 'exists:trade_operations,id'],
            'path' => ['required', 'string'],
            'uploaded_by' => ['nullable', 'integer'], // optional hint for precise match
        ]);

        $trade = TradeOperation::findOrFail($data['trade_id']);
        $rawPath = $data['path'];

        // Normalize path: handle full URLs, /storage prefix, backslashes, and query strings
        $normalize = function ($p) {
            if ($p === null) return null;
            // Strip query string
            $p = is_string($p) ? explode('?', $p, 2)[0] : $p;
            // If full URL, keep only path
            if (is_string($p) && preg_match('#^https?://#i', $p)) {
                $parsed = parse_url($p);
                $p = $parsed['path'] ?? $p;
            }
            // Remove leading /storage/ if present (public disk URL)
            $p = preg_replace('#^/+storage/+?#i', '', $p);
            // Normalise slashes and trim leading slashes
            $p = ltrim(str_replace('\\', '/', (string) $p), '/');
            return $p;
        };

    $path = $normalize($rawPath);
    $requestedUploaderId = isset($data['uploaded_by']) ? (int) $data['uploaded_by'] : null;

        // Authorization logic
        $user = $request->user();
        $isSuper = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
        // Allow users who can update the trade (policy) to delete as well
        $canUpdate = $user && method_exists($user, 'can') && $user->can('update', $trade);

        $canDelete = false;

        /*
        |--------------------------------------------------------------------------
        | Check Attachments
        |--------------------------------------------------------------------------
        */
        $attachments = (array) ($trade->attachments ?? []);
        foreach ($attachments as $entry) {
            $entryPathRaw = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryPath = $normalize($entryPathRaw);
            if ($entryPath !== $path) continue;

            // If client provides an uploader id, ensure it matches this entry (avoid conflicts when same path reused)
            $entryUploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
            if (!is_null($requestedUploaderId) && (int) $requestedUploaderId !== (int) ($entryUploader ?? 0)) {
                // Not the targeted entry
                continue;
            }

            // Superadmin can delete anything
            if ($isSuper || $canUpdate) { $canDelete = true; break; }

            // Check uploader
            $uploader = $entryUploader;
            if ($user && $uploader && (int) $user->id === (int) $uploader) { $canDelete = true; break; }

            // Fallback if no uploader info
            if (! $uploader) {
                $basename = basename($entryPath);
                if (preg_match('/^uid(\d+)__/i', $basename, $m)) {
                    $ownerId = (int) ($m[1] ?? 0);
                    if ($ownerId && $user && (int) $user->id === $ownerId) { $canDelete = true; break; }
                }
            }

            break;
        }

        /*
        |--------------------------------------------------------------------------
        | Check Photos if not found in attachments
        |--------------------------------------------------------------------------
        */
        if (! $canDelete) {
            $photos = (array) ($trade->photos ?? []);
            foreach ($photos as $entry) {
                $entryPathRaw = is_string($entry) ? $entry : ($entry['path'] ?? ($entry[0] ?? null));
                $entryPath = $normalize($entryPathRaw);
                if ($entryPath === $path) {
                    // If client provides an uploader id, ensure it matches this entry
                    $entryUploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);
                    if (!is_null($requestedUploaderId) && (int) $requestedUploaderId !== (int) ($entryUploader ?? 0)) {
                        // Not the targeted entry
                        continue;
                    }
                    if ($isSuper || $canUpdate) { $canDelete = true; break; }

                    $uploader = $entryUploader;
                    if ($user && $uploader && (int) $user->id === (int) $uploader) { $canDelete = true; break; }

                    if (! $uploader) {
                        $basename = basename($entryPath);
                        if (preg_match('/^uid(\d+)__/i', $basename, $m)) {
                            $ownerId = (int) ($m[1] ?? 0);
                            if ($ownerId && $user && (int) $user->id === $ownerId) { $canDelete = true; break; }
                        }
                    }
                    break;
                }
            }
        }

        if (! $canDelete) {
            abort(403, 'Action non autorisée');
        }

        /*
        |--------------------------------------------------------------------------
        | Delete file from storage
        |--------------------------------------------------------------------------
        */
        try {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Throwable $e) {
            // ignore delete errors
        }

        /*
        |--------------------------------------------------------------------------
        | Update Attachments
        |--------------------------------------------------------------------------
        */
        $changed = false;
        $filtered = collect($attachments)->filter(function ($entry) use ($path, &$changed, $user, $isSuper, $canUpdate, $normalize, $requestedUploaderId) {
            $entryPathRaw = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryPath = $normalize($entryPathRaw);
            if ($entryPath !== $path) return true;

            $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);

            // If uploaded_by is provided in request, only remove when it matches
            if (!is_null($requestedUploaderId) && (int) $requestedUploaderId !== (int) ($uploader ?? 0)) {
                return true; // keep this entry; it's not the targeted one
            }
            if ($isSuper || $canUpdate || ($user && $uploader && (int) $user->id === (int) $uploader)) {
                $changed = true;
                return false;
            }

            if (! $uploader) {
                $basename = basename($entryPath);
                if (preg_match('/^uid(\d+)__/i', $basename, $m)) {
                    $ownerId = (int) ($m[1] ?? 0);
                    if ($ownerId && $user && (int) $user->id === $ownerId) {
                        $changed = true;
                        return false;
                    }
                }
            }

            return true;
        })->values()->all();

        if ($changed) {
            $trade->attachments = $filtered;
            $trade->save();
            return back()->with('status', 'Document supprimé');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Photos
        |--------------------------------------------------------------------------
        */
        $photos = (array) ($trade->photos ?? []);
        $changedPhoto = false;
        $filteredPhotos = collect($photos)->filter(function ($entry) use ($path, &$changedPhoto, $user, $isSuper, $canUpdate, $normalize, $requestedUploaderId) {
            $entryPathRaw = is_string($entry) ? $entry : ($entry[0] ?? $entry['path'] ?? null);
            $entryPath = $normalize($entryPathRaw);
            if ($entryPath !== $path) return true;

            $uploader = is_string($entry) ? null : ($entry['uploaded_by'] ?? null);

            if (!is_null($requestedUploaderId) && (int) $requestedUploaderId !== (int) ($uploader ?? 0)) {
                return true; // not the targeted one
            }
            if ($isSuper || $canUpdate || ($user && $uploader && (int) $user->id === (int) $uploader)) {
                $changedPhoto = true;
                return false;
            }

            if (! $uploader) {
                $basename = basename($entryPath);
                if (preg_match('/^uid(\d+)__/i', $basename, $m)) {
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
            $trade->photos = $filteredPhotos;
            $trade->save();
            return back()->with('status', 'Photo supprimée');
        }

        return back()->with('status', 'Aucun document trouvé');
    }

    /**
     * Signed GET endpoint that delegates to destroy().
     * Accepts: trade_id, path, optional uploaded_by; requires valid signature.
     */
    public function destroySigned(Request $request)
    {
        // Ensure the request is signed; middleware also enforces this
        if (! $request->hasValidSignature()) {
            abort(403, 'Lien invalide ou expiré');
        }

        $tradeId = $request->query('trade_id');
        $path = $request->query('path');
        $uploadedBy = $request->query('uploaded_by');
        $ajax = (bool) $request->boolean('ajax');

        // Build a new request-like instance that mimics a POST to reuse validation/logic
        $internal = Request::create('/trades/attachments/delete', 'POST', [
            'trade_id' => $tradeId,
            'path' => $path,
            'uploaded_by' => $uploadedBy,
        ]);
        $internal->setUserResolver(fn () => $request->user());
        $response = $this->destroy($internal);

        // Optional explicit redirect target to avoid mobile referer quirks
        $redirect = (string) $request->query('redirect', '');
        if ($ajax) {
            return response()->json(['ok' => true, 'status' => session('status', 'Document supprimé')]);
        }
        if ($redirect !== '') {
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

        if (!($request->ajax() || $request->expectsJson())) {
            return redirect()->back()->with('status', session('status', 'Document supprimé'));
        }
        return $response;
    }
}
