<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\Category;
use App\Traits\GeneralTrait;
use Tymnon\jWTAuth\Facades\JWTAuth;

class CategoriesController extends Controller
{
    //
    use GeneralTrait;
    public function index(){
        try{
        $cateogries = Category::get();
        if(!$cateogries){
            return $this->returnError('001','Not Found');
        }
        return $this -> returnData('categories',$cateogries);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    public function get_category_by_id(Request $request)
    {
        try{
        $cateogry = Category::find($request -> id);
        if(!$cateogry){
            return $this->returnError('001','Not Found');
        }
        return $this -> returnData('category',$cateogry);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    public function search_category(Request $request)
    {
        try{
        $category = Category::where('name', 'like','%'.$request->search.'%')->get();
        if(!$category){
            return $this->returnError('001','Not Found');
        }
        return $this->returnData('category', $category);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
}