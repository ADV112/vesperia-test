<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class APIController extends Controller
{
    protected $sectionModel;
    public function __construct(
        Section $sectionModel
    ) {
        $this->sectionModel = $sectionModel;
    }

    function allForm(): JsonResponse {
        return response()->json($this->sectionModel->with(['input', 'input.options'])->get());
    }
}
