<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    function index() {

        $filters = request()->all('name','status','category');
            $categories = Category::filter(request()->only('name','status','category'))
            ->ordering(request()->only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'status' => $category->status,
            ]);

        return Inertia::render('Admin/Category/CategoryList',compact('categories','filters'));
    }


    public function create()
    {
      if(request()->isMethod('post')){
      
       request()->validate([
          'name' => 'required',
          'status' => 'required',
        ]);
        $category = new Category();
        $category->name = request()->name;
        $category->parent_id = request()->category;
        $category->status = request()->status ?? 1;
        $category->save();
        session()->flash('success', 'User successfully created');
        return redirect()->route('admin.category');
      }

      return Inertia::render('Admin/Category/CreateEdit');

   

    }



    public function update(Category $category)
    {
    
      if(request()->isMethod('post')){
        $credentials = request()->validate([
          'name' => 'required',
          'status' => 'required',
        ]);
        $category->name = request()->name;
        $category->status = request()->status ?? 1;
        $category->save();

        session()->flash('success', 'Category successfully updated');
        return redirect()->route('admin.category');
      }

    

      return Inertia::render('Admin/Category/CreateEdit',compact('category'));
    }

    public function delete(Category $category)
    {
      try{
      $category->delete();
      session()->flash('success', 'Category successfully deleted');
      return back();
      } catch (\Exception $e) {
        Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        return back()->with('error','Server error');
    }
    }

    public function changeStatus(Category $category)
    {
      try{
      $category->status = ($category->status == 1) ? 0 : 1 ;
      $category->save();
      session()->flash('success', 'Category status successfully changed');
      return back();
    } catch (\Exception $e) {
      Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return back()->with('error','Server error');
  }
    }


}
