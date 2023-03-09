<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Appointment;
use App\Models\Models\Expert;
use App\Models\Models\Question;

use App\Traits\GeneralTrait;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AppointmentController extends Controller
{
    //
    use GeneralTrait;
    public function create_appointment(Request $request)
    {
        try {
            $data = $request->only('date', 'time', 'price');
            $validator = Validator::make($data, [
                'date' => 'required',
                'time' => 'required',
                'price' => 'required'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            $user = Auth::guard('user-api')->user();
            $expert = $user->expert;

            $appointment = Appointment::create([
                'expert_id' => $expert->id,
                'date' => $request->date,
                'time' => $request->time,
                'price' => $request->price,
                'available' => '1',
            ]);

            return $this->returnData('appointment', $appointment);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_appointment(Request $request)
    {
        try {
            $data = $request->only('date', 'time', 'price');
            $validator = Validator::make($data, [
                'date' => 'required',
                'time' => 'required',
                'price' => 'required',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            $user = Auth::guard('user-api')->user();
            $appointment = Appointment::find($request->id);
            $expert = $user->expert;

            if ($appointment->expert_id !== $expert->id) {
                return $this->returnError('E201', "This Appointment is not for you");
            }
            if ($appointment->available !== 1) {
                return $this->returnError('E202', "This Appointment is not available");
            }

            $appointment->date = $request->date;
            $appointment->time = $request->time;
            $appointment->price = $request->price;
            $appointment->save();
            return $this->returnData('appointment', $appointment);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_appointment($id)
    {
        try {
            $user = Auth::guard('user-api')->user();
            $appointment = Appointment::find($id);
            $expert = $user->expert;

            if ($appointment->expert_id !== $expert->id) {
                return $this->returnError('E201', "This Appointment is not for you");
            }

            if ($appointment->available !== 1) {
                return $this->returnError('E202', "This Appointment is not available");
            }
            $result = $appointment->delete();
            return $this->returnSuccessMessage("appointment has been deleted");
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function index(Request $request)
    {
        try{
            $appointment = Appointment::where('available', '1')->where('expert_id', $request->expert_id)->get();
            return $this->returnData('appointment', $appointment);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_appointment(Request $request)
    {
        try{
            $appointment = Appointment::find($request->id);
            if(!$appointment)
            {
                return $this->returnError('E203', "there is no appoointment with this id");
            }
            return $this->returnData('appointment', $appointment);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}