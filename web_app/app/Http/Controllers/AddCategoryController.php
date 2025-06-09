<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;

class AddCategoryController extends Controller
{
    public function newCategoryView(Request $req)
    {
        if (Auth::user()->type == 'Admin') {
            return view('admin.newCategory');
        } else {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
    }

    public function newCategorySubmit(Request $req)
    {
        if (Auth::user()->type == 'Admin') {
            $validated = $req->validate([
                'name' => 'required',
                'description' => 'nullable',
                'show_on_homepage' => 'nullable',
            ]);
            $category = new Category;
            $category->name = $req->name;
            $category->description = $req->description;
            $category->show_on_homepage = $req->show_on_homepage;
            $saved = $category->save();
            if ($saved) {
                return redirect(route('home'))->with('msg', __('messages.categoryAddedSuccessfully'));
            } else {
                return redirect(route('home'))->with('error', __('messages.errorAddingCategory'));
            }
        } else {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
    }
}
