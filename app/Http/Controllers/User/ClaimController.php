<?php 
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        Claim::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Klaim berhasil diajukan, tunggu verifikasi admin.');
    }

    public function history()
    {
        $claims = Claim::where('user_id', Auth::id())->with('item')->latest()->get();
        return view('user.claims.history', compact('claims'));
    }
}
