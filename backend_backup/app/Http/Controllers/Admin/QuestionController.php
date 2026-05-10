<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\LegalProcedure;
use App\Models\ProcedureStep;
use App\Models\UnansweredQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = UnansweredQuestion::with('language')->where('resolved', false)->get();
        return response()->json($questions);
    }

    public function resolve(Request $request, UnansweredQuestion $question)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $question->update([
            'resolved' => true,
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json($question);
    }

    public function convert(Request $request, UnansweredQuestion $question)
    {
        $request->validate([
            'legal_category_id' => 'required|exists:legal_categories,id',
            'language_id' => 'required|exists:languages,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'summary' => 'nullable|string',
            'steps' => 'array',
            'keywords' => 'array',
            'admin_notes' => 'nullable|string',
        ]);

        $procedure = LegalProcedure::create($request->only([
            'legal_category_id',
            'language_id',
            'title',
            'description',
            'summary',
        ]));

        if ($request->steps) {
            foreach ($request->steps as $step) {
                ProcedureStep::create(array_merge($step, ['legal_procedure_id' => $procedure->id]));
            }
        }

        if ($request->keywords) {
            foreach ($request->keywords as $kw) {
                Keyword::create(array_merge($kw, ['legal_procedure_id' => $procedure->id]));
            }
        }

        $question->update([
            'resolved' => true,
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json(['question' => $question, 'procedure' => $procedure]);
    }
}
