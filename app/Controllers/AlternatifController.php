<?php

namespace App\Controllers;

use App\Models\AlternatifModel;

class AlternatifController extends BaseController
{
    public function index()
    {
        $model = new AlternatifModel();
        $data['alternatif'] = $model->findAll();
        return view('alternatif_view', $data);
    }

    public function viewTambah()
    {
        return view('alternatif_add_view');
    }

    public function add()
    {
        $model = new AlternatifModel();
        $data = [
            'nama_alternatif' => $this->request->getPost('nama_alternatif'),
        ];
        $model->insert($data);
        return redirect()->to('/alternatif');
    }

    public function delete($id)
    {
        $model = new AlternatifModel();
        $model->delete($id);
        return redirect()->to('/alternatif');
    }

    public function edit($id)
    {
        $model = new AlternatifModel();
        $data['alternatif'] = $model->find($id);
        return view('alternatif_edit_view', $data);
    }

    public function update($id)
    {
        $model = new AlternatifModel();
        $data = [
            'nama_alternatif' => $this->request->getPost('nama_alternatif'),
        ];
        $model->update($id, $data);
        return redirect()->to('/alternatif');
    }
}
