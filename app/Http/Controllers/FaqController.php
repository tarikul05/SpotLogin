<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Category;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('category')->get();
        return view('pages.faqs.index', compact('faqs'));
    }

    public function tutos()
    {
        $faqs = Faq::with('category')->get();
        return view('pages.faqs.tutos', compact('faqs'));
    }

    public function tutosShow(Faq $faq)
    {
        return view('pages.faqs.show-tutos', compact('faq'));
    }

    public function add(Request $request)
    {
        $categories = Category::all();
        return view('pages.faqs.form', compact('categories'));
    }

    public function edit(Faq $faq)
    {
        $categories = Category::all();
        return view('pages.faqs.form', compact('faq', 'categories'));
    }

    public function show(Faq $faq)
    {
        return view('pages.faqs.show', compact('faq'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'required',
            'youtube_link' => 'required|url',
        ]);

        Faq::create($data);

        return redirect()->route('faqs.list')->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'required',
            'youtube_link' => 'required|url',
        ]);

        $faq->update($data);

        return redirect()->route('faqs.list')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faqs.list')->with('success', 'FAQ deleted successfully.');
    }

}
