<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
            ->eloquent($query)
            ->addColumn('role_id', function ($row) {
                return $row->role ? $row->role->role : "N/A";
            })
            ->addColumn('phone', function ($row) {
                return "+1" . str_replace('-', '', $row->phone);
            });
        $data->addColumn('action', function ($row) {

            $edit = "<a href=" . url('staff/' . encrypt($row->id) . '/edit') . " title='Edit User' class='text-white btn btn-info btn-sm btn-flat'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;";
            $delete = '<button  data-id="' . encrypt($row->id) . '"  class="btn btn-danger btn-sm deleted" title="Delete  User!"><i class="fa fa-trash"></i></button>&nbsp;&nbsp;';

            return $edit . $delete;
        });
        return $data->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->applyScopes(User::select('users.*')->where('role_id','!=',1));
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('id')->title('Sr. #'),
            Column::make('email'),
            Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
            Column::make('phone')->title('Phone number'),
            Column::make('address'),
            Column::make('role_id')->title('Designation'),
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
        return 'User_' . date('YmdHis');
    }
}
