<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Appointment;
use App\Models\Models\Book;
use App\Models\Models\Expert;
use App\Traits\GeneralTrait;
use Auth;
use Illuminate\Http\Request;
use Nette\Schema\Expect;
use Validator;

class BookController extends Controller
{
    //
    use GeneralTrait;
    public function create_book(Request $request)
    {
        try
        {
            $data = $request->only('appointment_id');
            $validator = Validator::make($data, [
                'appointment_id' => 'required|string'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            $user = Auth::guard('user-api')->user();
            $appointment = Appointment::find($request->appointment_id);

            $book = Book::create([
                'expert_id'=>$appointment->expert_id,
                'appointment_id'=>$request->appointment_id,
                'user_id'=>$user->id,
            ]);

            return $this->returnData('book', $book->with([
                'expert' => function ($q) {
                    $q->with([
                        'user' => function ($q) {
                                $q->select('name', 'id');
                            }
                    ])->select('user_id','id', 'address', 'image');
            },'user'=> function ($q) {
                $q->select('name', 'id');
            },'appointment' => function ($q) {
                $q->select('date', 'time', 'price');
            }])->get());
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function user_books()
    {
        try {
         $user = Auth::guard('user-api')->user();
        $book = Book::where('user_id',$user->id);
        return $this->returnData('book', $book->with([
            'expert' => function ($q) {
                $q->with([
                    'user' => function ($q) {
                            $q->select('name', 'id');
                        }
                ])->select('user_id', 'id', 'address', 'image');
            },
            'appointment' => function ($q) {
                $q->select('date', 'time', 'price','id');
            }])->get());
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function expert_books()
    {
        try
        {   
            $user = Auth::guard('user-api')->user();
            $expert = $user->expert;
            
            $book = Book::where('expert_id',$expert->id);

            return $this->returnData('book', $book->with([
                'user' => function ($q) {
                    $q->select('id', 'name');
            },
                'appointment'=> function ($q) {
                    $q->select('id', 'time', 'date', 'price');
            }])->get());
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
