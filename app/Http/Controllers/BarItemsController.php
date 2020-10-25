<?php

namespace App\Http\Controllers;

use App\Model\BarItems;
use App\Model\MainBarCategory;
use App\Model\SubBarCategory;
use Illuminate\Http\Request;

class BarItemsController extends Controller
{
    //

    public function index()
    {
        $bar = BarItems::all();
        $bar->map(function ($bar) {
            if ($bar->sub_bar_category_id != null) {
                $bar->subBarCategory;
                $bar->subBarCategory->mainBarCategory;
            }
            if ($bar->main_bar_category_id != null) {
                $bar->mainBarCategory;
            }
        });
        if ($bar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of bars.', $bar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any bar yet.', $bar);
        }
    }

    public function getBarItemList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalBarItems = BarItems::get()->count();

        $bar = BarItems::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($bar->isNotEmpty()) {
            $bar->map(function ($bar) {
                if ($bar->sub_bar_category_id != null) {
                    $bar->subBarCategory;
                    $bar->subBarCategory->mainBarCategory;
                }
                if ($bar->main_bar_category_id != null) {
                    $bar->mainBarCategory;
                }
            });
            return $this->jsonResponse(true, 'Lists of bars.', $bar, $totalBarItems);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any bar yet.', $bar, $totalBarItems);
        }
    }

    public function store()
    {
        $bar = BarItems::create($this->validateRequest());
        return $this->jsonResponse(true, 'BarItems has been created successfully.', $bar);
    }

    public function show(BarItems $bar)
    {
        return $this->jsonResponse(true, 'Data of an individual BarItems.', $bar);
    }

    public function update(BarItems $bar)
    {
        $bar->update($this->validateRequest());
        return $this->jsonResponse(true, 'BarItems has been updated.', $bar);
    }

    public function destroy(BarItems $bar)
    {
        $bar->delete();
        return $this->jsonResponse(true, 'BarItems has been deleted successfully.');
    }

    public function getMainBarCategory()
    {
        $mainBar = MainBarCategory::all();
        if ($mainBar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main bars.', $mainBar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main bar yet.', $mainBar);
        }
    }

    public function getSubBarCategory()
    {
        $subBar = SubBarCategory::all();
        if ($subBar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of sub bars.', $subBar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any sub bar yet.', $subBar);
        }
    }

    private function validateRequest()
    {
        return request()->validate([
            'main_bar_category_id' => 'sometimes',
            'sub_bar_category_id' => 'sometimes',
            'bar_name' => 'required',
            'quantity' => 'sometimes',
            'price' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalBarItems=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalBarItems
        ]);
    }
}
