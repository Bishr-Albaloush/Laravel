<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\Expert;
use App\Traits\GeneralTrait;
use App\Models\User;

class ExpertController extends Controller
{
    //
    use GeneralTrait;
    public function index()
    {
        try {
            $expert = Expert::select('id', 'user_id')->with([
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])->get();
            if (!$expert) {
                return $this->returnError('001', 'Not Found');
            }
            return $this->returnData('experts', $expert);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function get_experts_by_category(Request $request)
    {
        try {
            $expert = Expert::with([
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])->where('category_id', $request->id)->select('id', 'user_id')->get();
            if (!$expert) {
                return $this->returnError('001', 'Not Found');
            }
            return $this->returnData('experts', $expert);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show_expert(Request $request)
    {
        try {
            $expert = Expert::with([
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])->find($request->id);
            return $this->returnData('expert', $expert);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function search_expert(Request $request)
    {
        try {
            $expert = User::with(['expert'])->select('id', 'name')
                ->where('name', 'like', '%' . $request->search . '%')->whereHas('expert')->get();
            return $this->returnData('expert', $expert);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}