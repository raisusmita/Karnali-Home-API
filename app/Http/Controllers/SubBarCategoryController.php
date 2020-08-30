<?php

namespace App\Http\Controllers;

use App\Model\SubBarCategory;
use Illuminate\Http\Request;

class SubBarCategoryController extends Controller
{
    //
    public function index()
    {
        $subBar = SubBarCategory::all();
        if ($subBar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of sub bars.', $subBar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any sub bar yet.', $subBar);
        }
    }

    public function store()
    {
        $subBar = SubBarCategory::create($this->validateRequest());
        return $this->jsonResponse(true, 'Sub Bar Category has been created successfully.', $subBar);
    }

    public function update(SubBarCategory $subBar)
    {
        $subBar->update($this->validateRequest());
        return $this->jsonResponse(true, 'Sub bar category has been updated.', $subBar);
    }

    private function validateRequest()
    {
        return request()->validate([
            'main_bar_category_id' => 'required',
            'sub_bar_name' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
