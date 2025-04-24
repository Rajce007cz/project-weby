<?php

namespace App\Controllers;

use App\Models\SeasonModel;
use CodeIgniter\Controller;

class SeasonController extends Controller
{
    public function index()
    {
        $model = new SeasonModel();
        $data['seasons'] = $model->orderBy('year', 'DESC')->findAll();

        return view('seasons/season', $data);
    }

    public function show($year)
    {
        $model = new SeasonModel();
        $season = $model->where('year', $year)->first();

        if (!$season) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Sezóna $year nenalezena");
        }

        $data['season'] = $season;
        return view('seasons/season_details', $data);
    }
}