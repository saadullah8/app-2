<?php

namespace App\DataTables;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CancelOrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $data = datatables()
            ->eloquent($query);
        $data->editColumn('order_no',function ($row){
            return '<a href="' . url('orderDetail/'.encrypt($row->id)) . '">'.$row->order_no.'</a>';
        });
        $data->addColumn('updated_at',function ($row){
            return Carbon::parse($row->updated_at)->format('d-M-Y h:i:s');
        })->addColumn('customer_id',function ($row){
            return $row->userDetail?$row->userDetail->first_name.' '.$row->userDetail->last_name:"N/A";
        });
        $data->addColumn('action', function ($row) {
            $sms='';
            $view = '<a class="btn btn-info btn-sm btn-flat"   href="' . url('orderDetail/'.encrypt($row->id)) . '"><i class="fa fa-eye" ></i><br>View</a>&nbsp;&nbsp;';
            $print='<a href="javascript:;" class="btn btn-sm btn-primary print_page print_btn" data-content="'.encrypt($row->id).'">
                            <i class="fa fa-print"></i><br> Print
                        </a>';
                if ($row->count_delete!=1){
                    $sms = '<a class="btn btn-success btn-sm btn-flat smsOrderBtn"   href="javascript:;" data-content='.encrypt($row->id).' ><i class="fa fa fa-envelope" ></i><br>Sms Send</a>&nbsp;&nbsp;';
                }else{
                    $sms = '<a class="btn btn-success btn-sm btn-flat disabled"   href="javascript:;"><i class="fa fa fa-envelope" ></i><br> Sms Send</a>&nbsp;&nbsp;';

                }
            return $view  . $sms.$print;
        });
        return $data->rawColumns(['action','created_at','order_no']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $orders = Order::where('order_status',2)->orderby('created_at','desc');
        return $this->applyScopes($orders);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('canceled-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'searching' => true,
                'processing' => true,
                'display' => true,
            ])
            ->orderBy(1);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('order_no')->title('Order No'),
            Column::make('customer_id')->title('Customer Name'),
            Column::make('updated_at')->title('Date & Time'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Process_' . date('YmdHis');
    }
}
