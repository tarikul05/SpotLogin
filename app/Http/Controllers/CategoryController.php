<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('pages.categories.index', compact('categories'));
    }

    public function add(Request $request)
    {
        return view('pages.categories.form');
    }

    public function edit(Category $category)
    {
        return view('pages.categories.form', compact('category'));
    }

    public function show(Category $category)
    {
        return view('pages.categories.show', compact('category'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create($data);

        return redirect()->route('categories.list')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', Rule::unique('categories')->ignore($category->id)],
        ]);

        $category->update($data);
        $categories = Category::all();
        return redirect()->route('categories.list')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.list')->with('success', 'Category deleted successfully.');
    }

}
