<?php

namespace App\Http\Controllers;

use App\Model\BarName;
use Illuminate\Http\Request;

class BarNameController extends Controller
{
    //

    public function index()
    {
        $barName = BarName::all();
        if ($barName->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of bar names.', $barName);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any bar names yet.', $barName);
        }
    }

    public function getBarNameList(Request $request)
    {
        $skip = $request->skip;
        $limit = $request->limit;
        $totalBarName = BarName::get()->count();

        $barName = BarName::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($barName->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of bar names.', $barName, $totalBarName);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main bar yet.', $barName, $totalBarName);
        }
    }

    public function store()
    {
        $barName = BarName::create($this->validateRequest());
        return $this->jsonResponse(true, 'Main Bar Category has been created successfully.', $barName);
    }

  
    public function update(BarName $barName)
    {
        $barName->update($this->validateRequest());
        return $this->jsonResponse(true, 'Main bar category has been updated.', $barName);
    }
 

    private function validateRequest()
    {
        return request()->validate([
            'bar_name' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalBarName = 0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount' => $totalBarName
        ]);
    }
}
