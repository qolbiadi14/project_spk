<?php

namespace App\Controllers;
use App\Models\KriteriaModel;

class KriteriaController extends BaseController
{
    public function index()
    {
        $model = new KriteriaModel();
        $data['kriteria'] = $model->findAll();
        return view('kriteria_view', $data);
    }
    
    public function viewTambah()
    {
        return view('kriteria_add_view');
    }
    
    public function add()
    {
        $model = new KriteriaModel();
        $data = [
            'nama_kriteria' => $this->request->getPost('nama_kriteria'),
            'tipe_kriteria' => $this->request->getPost('tipe_kriteria'),
            'bobot_kriteria' => $this->request->getPost('bobot_kriteria'),
        ];
        $model->insert($data);
        return redirect()->to('/kriteria');
    }

    public function delete($id)
    {
        $model = new KriteriaModel();
        $model->delete($id);
        return redirect()->to('/kriteria');
    }

    public function edit($id)
    {
        $model = new KriteriaModel();
        $data['kriteria'] = $model->find($id);
        return view('kriteria_edit_view', $data);
    }

    public function update($id)
    {
        $model = new KriteriaModel();
        $data = [
            'nama_kriteria' => $this->request->getPost('nama_kriteria'),
            'tipe_kriteria' => $this->request->getPost('tipe_kriteria'),
            'bobot_kriteria' => $this->request->getPost('bobot_kriteria'),
        ];
        $model->update($id, $data);
        return redirect()->to('/kriteria');
    }
}
