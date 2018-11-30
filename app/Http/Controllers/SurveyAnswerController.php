<?php

namespace App\Http\Controllers;
use App\SurveyAnswer;
use Illuminate\Http\Request;

class SurveyAnswerController extends Controller
{
    public function index()
    {
        $this->middleware('admin');
        $survey_answers = SurveyAnswer::all();
        return view('admin.surveys.survey_answers', compact('survey_answers'));        
    }
}
