<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class CompanyController extends Controller
{
    public function detailCompany(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'companyid' => 'required|string',
            ]);

            if($validate->fails()) {
                return ApiResponse::responseWarning("Isi kode company terlebih dahulu");
            }

            $sql = DB::table('company')
                    ->where('companyid', $request->get('companyid'))
                    ->first();

            return ApiResponse::responseSuccess('success', $sql);
        } catch (\Exception $exception) {
            return ApiResponse::responseError($request->get('user_id'), 'API', Route::getCurrentRoute()->action['controller'],
                        $request->route()->getActionMethod(), $exception->getMessage(), $request->get('companyid'));
        }
    }
}
