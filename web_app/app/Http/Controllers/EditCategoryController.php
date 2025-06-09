<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;

class EditCategoryController extends Controller
{
    public function editCategoryView(Request $req, $categoryId)
    {
        if (Auth::user()->type != 'Admin') {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
        $category = Category::where('id', '=', $categoryId)->first();
        return view('admin.editCategory', compact('category'));
    }

    public function editCategorySubmit(Request $req, $categoryId)
    {
        if (Auth::user()->type != 'Admin') {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
        $validated = $req->validate([
            'name' => 'required',
            'description' => 'nullable',
            'show_on_homepage' => 'nullable',
        ]);
        $category = Category::where('id', '=', $categoryId)->first();
        $category->name = $req->name;
        $category->description = $req->description;
        $category->show_on_homepage = $req->show_on_homepage;
        $saved = $category->save();
        if (!$saved) {
            return redirect(route('home'))->with('error', __('messages.errorEditingCategory'));
        }
        return redirect(route('home'))->with('msg', __('messages.categoryEdited'));
    }
}
