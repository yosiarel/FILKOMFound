<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Models\Claim;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        $verifications = Verification::with(['claim', 'claim.user', 'claim.item'])->latest()->get();
        return view('admin.verifications.index', compact('verifications'));
    }

    public function show($id)
    {
        $verification = Verification::with(['claim', 'claim.user', 'claim.item'])->findOrFail($id);
        return view('admin.verifications.show', compact('verification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'note' => ['nullable', 'string']
        ]);

        $verification = Verification::findOrFail($id);
        $verification->status = $request->status;
        $verification->note = $request->note;
        $verification->save();

        return redirect()->route('admin.verifications.index')->with('success', 'Verifikasi diperbarui.');
    }
}
