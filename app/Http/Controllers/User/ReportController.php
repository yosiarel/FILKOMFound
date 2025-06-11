<?php 
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        return view('user.reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:lost,found',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image')?->store('reports', 'public');

        Report::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'image' => $path,
        ]);

        return redirect()->route('user.reports.history')->with('success', 'Laporan berhasil dikirim.');
    }

    public function history()
    {
        $reports = Report::where('user_id', Auth::id())->latest()->get();
        return view('user.reports.history', compact('reports'));
    }
}
