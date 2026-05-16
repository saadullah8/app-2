<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promo\EditPromoRequest;
use Exception;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Promo\StorePromoRequest;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
        PromoCode::where('expiredAt','<',strtotime(now()->startOfDay()))->update(['status' => 'Expired']);

    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(PromoCode::all())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn='';
                    $btn = ' <a href="#" data-id="' . $row->id . '" class="btn btn-sm btn-info promo_edit_btn" title="Edit Promo code!"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';

                    if ($row->status == 'Expired') {
//                        $btn .= '<a href="' . url("promos/" . $row->id . "/") . '/' . $row->status . '"  data-id="' . $row->id . '"  class="btn btn-primary btn-sm text-success promo_lock_btn" title="Mark as active promo code!"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                    } else {
                        $btn .= '<a href="' . url("promos/" . $row->id . "/") . '/' . $row->status . '/lock"  data-id="' . $row->id . '"  class="btn btn-warning btn-sm text-info promo_lock_btn" title="Mark as expired promo code!"><i class="fa fa-lock"></i></a>&nbsp;&nbsp;';
                    }
                    $btn .= '<a href="' . url("promos/" . $row->id . "") . '/delete"  class="btn  btn-danger btn-sm  text-danger promo_delete_btn" title="Discontinue promo code!"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })

                ->editColumn('createdAt', function ($row) {
                    return date('m-d-Y', strtotime($row->createdAt));
                })
                ->editColumn('discountValue', function ($row) {
                    return $row->discountValue . '%';
                })

                ->editColumn('expiredAt', function ($row) {
                    return date('m-d-Y', $row->expiredAt);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'Active') {
                        return '<span class=" badge badge-success" style="background-color: #28a745">Active</span>';
                    }
                    if ($row->status == 'Expired') {
                        return '<span class="badge badge-danger" style="background-color: #dc3545">Expired</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('promo.promos');
    }

    /**
     * store promo
     *
     * @param  mixed $storePromoRequest
     * @return void
     */
    public function store(StorePromoRequest $storePromoRequest)
    {
        try {
            $promo = PromoCode::create(
                [
                    'code' => $storePromoRequest->promoCode,
                    'discountValue' => $storePromoRequest->discountValue,
                    'expiredAt' => strtotime($storePromoRequest->expiryDate)
                ]
            );
            if ($promo->id) {
                toastr()->Success('Promo code added successfully');
                return back();
            }
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * edit promo with ajax
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        try {
            return response()->json(['status' => true, 'data' => PromoCode::find($id)], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'data' => $e->getMessage()], JsonResponse::HTTP_OK);
        }
    }
    /**
     * update promo
     *
     * @param  mixed $request
     * @return void
     */
    public function update(EditPromoRequest $request)
    {
        try {
            $promo = PromoCode::find($request->id);
            $updated=$promo->update(
                [
                    'discountValue' => $request->has('discountValue') ? $request->discountValue : $promo->discountValue,
                    'expiredAt' => $request->has('expiryDate') ? strtotime($request->expiryDate) : $promo->expiredAt
                ]
            );
            if ($updated) {
                toastr()->Success('Promo code updated successfully');
                return back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * delete promo
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $promo = PromoCode::find($id)->delete();
            if ($promo) {
                toastr()->Success('Promo code deleted successfully');
                return back();
            }
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }
    /**
     * activate promo
     *
     * @param  mixed $id
     * @return void
     */
    public function activate($id)
    {
        try {
            $promo = PromoCode::find($id)->update(['status' => 'Active']);
            if ($promo) {
                toastr()->Success('Promo code updated successfully');
                return back();
            }
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    /**
     * lock promo as expired
     *
     * @param  mixed $id
     * @return void
     */
    public function lock($id)
    {
        try {
            $promo = PromoCode::find($id)->update(['status' => 'Expired']);
            if ($promo) {
                toastr()->Success('Promo code updated successfully');
                return back();
            }
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }
    public function isAlreadyExists($promoCode)
    {
        try {
            $promo = PromoCode::where('code',$promoCode)->exists();
            if ($promo) {
                return response()->json(['status' => true, 'message' => 'The promo Code has already been taken']);
            }
            return response()->json(['status' => false]);
        } catch (Exception $e) {
            return response()->json(['status' => true, 'message' => $e->getMessage()],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
