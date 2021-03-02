<?php

namespace App\Http\Controllers;

use App\Model\BarItems;
use App\Model\BarName;
use App\Model\MainBarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarItemsController extends Controller
{
    //

    public function index()
    {
        $bar = BarItems::all();
        $bar->map(function ($bar) {
            if ($bar->main_bar_category_id != null) {
                $bar->mainBarCategory;
            }
            if ($bar->bar_name_id != null) {
                $bar['bar_name'] = BarName::find($bar->bar_name_id)->bar_name;
            }
        });
        if ($bar->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of bars.', $bar);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any bar yet.', $bar);
        }
    }

    public function getBarItemList(Request $request)
    {
        $skip = $request->skip;
        $limit = $request->limit;
        $totalBarItems = BarItems::get()->count();

        $bar = BarItems::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($bar->isNotEmpty()) {
            $bar->map(function ($bar) {
                if ($bar->main_bar_category_id != null) {
                    $bar->mainBarCategory;
                }
                if ($bar->bar_name_id != null) {
                    $bar['bar_name'] = BarName::find($bar->bar_name_id)->bar_name;
                }
            });
            return $this->jsonResponse(true, 'Lists of bars.', $bar, $totalBarItems);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any bar yet.', $bar, $totalBarItems);
        }
    }

    public function getBarItemsById()
    {
        $barList = array(
            "barItems" => []
        );

        // $barItems = BarName::get();
        // foreach ($barItems as $key => $bName) {
        //     $bName->BarItems->where('main_bar_category_id', request()->id);
        //     // WIP: filter out unrelated data
        //     if (empty($bName->barItems)) {
        //         unset($barItems[$key]);
        //     }
        // }

        $barItems = BarName::with(['barItems' => function ($q) {
            $q->where('main_bar_category_id', request()->id); // '=' is optional
        }])->get();

        // $val = [];
        // foreach ($barItems as $key => $value) {
        //     if (!empty($value->bar_items)) {
        //         // return $value->bar_items;
        //         // unset($barItems[$key]);
        //     } else {
        //         array_push($val, $value);
        //     }
        // }
        // return $val;
        // $barItems = BarItems::groupBy('bar_name_id')->whereNotNull('bar_name_id')->where('main_bar_category_id', request()->id)->get();
        $barList["barItems"] = $barItems;
        return $this->jsonResponse(true, 'Lists of bars items.', $barList);
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

    private function validateRequest()
    {
        return request()->validate([
            'main_bar_category_id' => 'sometimes',
            'bar_name_id' => 'sometimes',
            'quantity' => 'required',
            'price' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalBarItems = 0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount' => $totalBarItems
        ]);
    }
}
