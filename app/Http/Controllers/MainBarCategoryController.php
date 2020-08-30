<?php

namespace App\Http\Controllers;

use App\Model\MainBarCategory;
use Illuminate\Http\Request;

class MainBarCategoryController extends Controller
{
    public function index()
    {
        $mainBar = MainBarCategory::all();
        if ($mainBar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main bars.', $mainBar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main bar yet.', $mainBar);
        }
    }

    public function store()
    {
        $mainBar = MainBarCategory::create($this->validateRequest());
        return $this->jsonResponse(true, 'Main Bar Category has been created successfully.', $mainBar);
    }

    // public function show($id)
    // {
    //     //
    // }

    public function update(MainBarCategory $mainBar)
    {
        $mainBar->update($this->validateRequest());
        return $this->jsonResponse(true, 'Main bar category has been updated.', $mainBar);
    }
    // public function destroy(MainBarCategory $mainBar)
    // {
    //     $mainBar->delete();
    //     return $this->jsonResponse(true, 'BarItems has been deleted successfully.');
    // }

    private function validateRequest()
    {
        return request()->validate([
            'main_bar_name' => 'required',
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
