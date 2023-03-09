<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\Category;
use App\Models\Models\Question;
use App\Models\Models\Answer;
use App\Models\Models\Expert;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Auth;

class ConsultationController extends Controller
{
    //
    use GeneralTrait;
    public function create_question(Request $request)
    {
        try
        {
            $data = $request->only('expert_id', 'text');
            $validator = Validator::make($data, [
                'expert_id' => 'required',
                'text' => 'required|string'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            $user = Auth::guard('user-api')->user();
            $expert = Expert::find($request->expert_id);
            
            $question = Question::create([
                'expert_id'=>$request->expert_id,
                'user_id'=>$user->id,
                'category_id'=>$expert->category_id,
                'text' => $request->text
            ]);

            return $this->returnData('question', $question);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function create_answer(Request $request)
    {
        try
        {
            $data = $request->only('question_id', 'text');
            $validator = Validator::make($data, [
                'question_id' => 'required',
                'text' => 'required|string'
            ]);
             
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            $user = Auth::guard('user-api')->user();
            $question = Question::find($request->question_id);
            $expert = $user->expert;
            
            if(!$question->answer && $question->expert_id == $expert->id){
                $answer = Answer::create([
                    'expert_id'=>$expert->id,
                    'question_id'=>$request->question_id,
                    'text' => $request->text
                ]);

                return $this->returnData('answer', $answer);}
            else {
                return $this->returnError('E201', "the question was answerd before or it is not for you");
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function index(){
        try {
        $questions = Question::with([
            'user' => function($q){
            $q -> select('id','name');},
            'expert'=> function($q){
            $q -> select('id', 'user_id') -> with(['user' => function($qq){$qq -> select('id','name');}]);}
            ])->get();
        
        if(!$questions){
                return $this->returnError('001','Not Found');
        }
        return $this->returnData('consultations',$questions);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
       
    }

    public function get_questions_by_category(Request $request)
    {
        try{
        $questions = Question::with(['user' => function($q){
            $q -> select('id','name');
        },'expert'=> function($q){
            $q -> select('id', 'user_id') -> with(['user' => function($qq){$qq -> select('id','name');}]);}, 'answer'
            ])->where('category_id',$request->id)->get();
        
        if(!$questions){
                return $this->returnError('001','Not Found');
        }
        return $this->returnData('consultations',$questions);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    public function show_consultation(Request $request)
    {
        try{
        $consult = Question::with(['user' => function($q){
            $q -> select('id','name');
        },'expert'=> function($q){
            $q -> select('id', 'user_id') -> with(['user' => function($qq){$qq -> select('id','name');}]);}, 'answer'
            ])->find($request->id);        
        return $this->returnData('consultation', $consult);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    public function search_consultation(Request $request)
    {
        try{
        $consult = Question::with(['user' => function($q){
            $q -> select('id','name');
        },'expert'=> function($q){
            $q -> select('id', 'user_id') -> with(['user' => function($qq){$qq -> select('id','name');}]);}, 'answer'
            ])->where('text','like','%'.$request->search.'%')->orwherehas('answer',function($q)use($request){
                $q->where('text','like','%'.$request->search.'%');
            })->get();
        
        return $this->returnData('consultations', $consult);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
}
