<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    /**
     * Update preferensi kustomisasi dashboard milik user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        // Ambil preferensi saat ini atau berikan default jika belum ada
        $currentPreferences = $user->preferences ?? [
            'theme' => 'light',
            'show_map' => true,
            'show_table' => true,
            'compact_mode' => false,
        ];

        // Gabungkan preferensi lama dengan inputan baru
        $updatedPreferences = array_merge($currentPreferences, [
            'show_map' => filter_var($request->input('show_map'), FILTER_VALIDATE_BOOLEAN),
            'show_table' => filter_var($request->input('show_table'), FILTER_VALIDATE_BOOLEAN),
            'compact_mode' => filter_var($request->input('compact_mode'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // Simpan ke kolom preferences di database
        $user->update([
            'preferences' => $updatedPreferences
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Preferensi dashboard berhasil diperbarui!',
            'preferences' => $updatedPreferences
        ]);
    }
}