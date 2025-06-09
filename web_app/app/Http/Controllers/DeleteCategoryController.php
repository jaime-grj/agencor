<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;

class DeleteCategoryController extends Controller
{
    public function deleteCategoryView(Request $req, $categoryId)
    {
        if (Auth::user()->type != 'Admin') {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
        $category = Category::where('id', '=', $categoryId)->first();
        return view('admin.deleteCategory', compact('category'));
    }

    public function deleteCategorySubmit(Request $req, $categoryId)
    {
        if (Auth::user()->type != 'Admin') {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
        $category = Category::where('id', '=', $categoryId)->first();
        $category->delete();
        return redirect(route('home'))->with('msg', __('messages.categoryDeleted'));
    }
}
