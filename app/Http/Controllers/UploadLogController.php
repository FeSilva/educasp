<?php

namespace App\Http\Controllers;

use App\Models\UploadLog;
use Illuminate\Http\Request;

class UploadLogController extends Controller
{
    private $model;

    public function __construct(UploadLog $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $logs = $this->model->with('user')->orderBy('data_envio', 'desc')->limit('30')->get();
        if (!empty($data['data_de']) || !empty($data['data_ate'])) {
            $logs = $this->model->where(function($query) use ($data)
            {
                $query->where('data_envio', '>=', $data['data_de'] . ' 00:00:00')
                    ->where('data_envio', '<=', $data['data_ate'] . ' 23:59:59');
            })->where('user_id', Auth()->user()->id)
            ->orderBy('data_envio', 'desc')->get();
        }
        return view('logs.upload', compact('logs'));
    }
}
