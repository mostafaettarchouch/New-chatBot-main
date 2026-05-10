<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\UnansweredQuestion;
use App\Models\LegalProcedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_questions' => Question::count(),
            'total_unanswered' => UnansweredQuestion::where('resolved', false)->count(),
            'total_procedures' => LegalProcedure::count(),
            'questions_by_language' => Question::join('languages', 'languages.id', '=', 'questions.language_id')
                ->select('languages.name as language', DB::raw('count(*) as count'))
                ->groupBy('languages.name')
                ->get(),
            'most_asked_procedures' => Question::join('legal_procedures', 'legal_procedures.id', '=', 'questions.legal_procedure_id')
                ->select('legal_procedures.title as procedure_title', DB::raw('count(*) as count'))
                ->whereNotNull('questions.legal_procedure_id')
                ->groupBy('legal_procedures.title')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }
}
