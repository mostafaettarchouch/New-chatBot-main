<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalProcedure;
use App\Models\LegalCategory;
use App\Models\Language;
use App\Models\ProcedureStep;
use App\Models\Keyword;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    public function index()
    {
        $procedures = LegalProcedure::with('legalCategory', 'language', 'procedureSteps', 'keywords')->get();
        return response()->json($procedures);
    }

    public function store(Request $request)
    {
        $request->validate([
            'legal_category_id' => 'required|exists:legal_categories,id',
            'language_id' => 'required|exists:languages,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'summary' => 'nullable|string',
            'steps' => 'array',
            'keywords' => 'array',
        ]);

        $procedure = LegalProcedure::create($request->only(['legal_category_id', 'language_id', 'title', 'description', 'summary']));

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

        return response()->json($procedure->load('procedureSteps', 'keywords'), 201);
    }

    public function show(LegalProcedure $procedure)
    {
        return response()->json($procedure->load('legalCategory', 'language', 'procedureSteps', 'keywords'));
    }

    public function update(Request $request, LegalProcedure $procedure)
    {
        $request->validate([
            'legal_category_id' => 'required|exists:legal_categories,id',
            'language_id' => 'required|exists:languages,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'summary' => 'nullable|string',
            'steps' => 'array',
            'keywords' => 'array',
        ]);

        $procedure->update($request->only(['legal_category_id', 'language_id', 'title', 'description', 'summary']));

        // Update steps - simple replace for now
        $procedure->procedureSteps()->delete();
        if ($request->steps) {
            foreach ($request->steps as $step) {
                ProcedureStep::create(array_merge($step, ['legal_procedure_id' => $procedure->id]));
            }
        }

        $procedure->keywords()->delete();
        if ($request->keywords) {
            foreach ($request->keywords as $kw) {
                Keyword::create(array_merge($kw, ['legal_procedure_id' => $procedure->id]));
            }
        }

        return response()->json($procedure->load('procedureSteps', 'keywords'));
    }

    public function destroy(LegalProcedure $procedure)
    {
        $procedure->delete();
        return response()->json(['message' => 'Deleted']);
    }
}