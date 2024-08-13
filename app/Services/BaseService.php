<?php


namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }


    public abstract function model();

    private function setModel(): void
    {
        $newModel = App::make($this->model());

        if (!$newModel instanceof Model)
            throw new \RuntimeException("Class {$newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        $this->model = $newModel;
    }

    protected function checkParamFilter($value): bool
    {
        return $value != '' && $value != null;
    }

    public function getPaginateByQuery($query, Request $request)
    {
        $without = ['orders', 'limit', 'offset'];
        $total = DB::query()->fromSub($query->cloneWithout($without), 'sub_table')->count();
        $currentPage = $request->current_page ? (int)$request->current_page : config('pagination.current_page');
        $perPage = $request->per_page ? (int)$request->per_page : config('pagination.per_page');
        if ($total == 0) {
            $lastPage = 1;
            $offSet = 0;
            $from = 0;
            $to = 0;
        } else {
            $lastPage = ceil($total / $perPage);
            $offSet = (($currentPage - 1) * $perPage);
            $from = $total ? ($offSet + 1) : 0;
            $to = $currentPage == $lastPage ? $total : ((($currentPage - 1) * $perPage) + $perPage);
        }
        $data = $query->offset($offSet ?: 0)->limit($perPage)->get();
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $currentPage,
                'from' => $from,
                'to' => $to,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total
            ]
        ];
    }

    public function responseSuccess($data, $message = null)
    {
        if ($message) {
            return response()->json(['success' => true, 'data' => $data, '  message' => $message], 200);
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function responseError($message, $status)
    {
        $status = $status ? $status : 500;
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
