<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Products;

class ContactController extends Controller
{
    public function index()
    {
        $brands = Products::select('brand_name')->distinct()->pluck('brand_name');

        return view('Contact', compact('brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($data);

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    public function adminIndex()
    {
        return view('messages');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message deleted successfully.');
    }
}
