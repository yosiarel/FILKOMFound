<?php 
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('user')->latest()->paginate(10);
        return view('user.items.index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);
        return view('user.items.show', compact('item'));
    }
}
