<?php

namespace App\Controllers;

use App\Models\AlternatifModel;
use App\Models\NilaiAlternatifModel;
use App\Models\KriteriaModel;

class PerangkinganAlternatifController extends BaseController
{
    public function index()
    {
        $alternatifModel = new AlternatifModel();
        $kriteriaModel = new KriteriaModel();
        $nilaiAlternatifModel = new NilaiAlternatifModel();

        $alternatif = $alternatifModel->findAll();
        $nilai = $nilaiAlternatifModel->findAll();
        $kriteria = $kriteriaModel->findAll();
        $minMax = $this->getMinMaxValues($kriteria);

        $data['alternatif'] = $alternatif;
        $data['kriteria'] = $kriteria;
        $data['nilai'] = $nilai;
        $data['normalisasi'] = $this->calculateNormalization($alternatif, $kriteria, $minMax);
        $data['terbobot'] = $this->calculateWeightedNormalization($data['normalisasi'], $kriteria);
        $data['scores'] = $this->calculateScores($data['normalisasi'], $kriteria);
        $data['ranking'] = $this->calculateRanking($data['scores'], $alternatif);

        return view('perangkingan_alternatif_view', $data);
    }

    public function store()
    {
        $nilaiAlternatifModel = new NilaiAlternatifModel();
        $kriteriaModel = new KriteriaModel();
        $kriteria = $kriteriaModel->findAll();

        foreach ($this->request->getPost() as $key => $value) {
            if (strpos($key, 'criteria_') === 0) {
                if (!is_numeric($value) || $value <= 0) {
                    session()->setFlashdata('error', 'Nilai harus berupa angka positif.');
                    return redirect()->back()->withInput();
                }
                list(, $id_kriteria, $id_alternatif) = explode('_', $key);
                $existingRecord = $nilaiAlternatifModel->where(['id_alternatif' => $id_alternatif, 'id_kriteria' => $id_kriteria])->first();
                if ($existingRecord) {
                    $nilaiAlternatifModel->update($existingRecord['id_nilai'], [
                        'value' => $value
                    ]);
                } else {
                    $nilaiAlternatifModel->insert([
                        'id_alternatif' => $id_alternatif,
                        'id_kriteria' => $id_kriteria,
                        'value' => $value
                    ]);
                }
            }
        }

        session()->setFlashdata('success', 'Berhasil menambahkan atau memperbarui nilai.');
        return redirect()->to('/perangkingan-alternatif');
    }

    public function normalisasi()
    {
        $alternatifModel = new AlternatifModel();
        $kriteriaModel = new KriteriaModel();
        $nilaiAlternatifModel = new NilaiAlternatifModel();

        $alternatif = $alternatifModel->findAll();
        $kriteria = $kriteriaModel->findAll();
        $minMax = $this->getMinMaxValues($kriteria);

        $data['alternatif'] = $alternatif;
        $data['kriteria'] = $kriteria;
        $data['normalisasi'] = $this->calculateNormalization($alternatif, $kriteria, $minMax);
        $data['terbobot'] = $this->calculateWeightedNormalization($data['normalisasi'], $kriteria);
        $data['scores'] = $this->calculateScores($data['terbobot'], $kriteria);

        return view('normalisasi_view', $data);
    }

    private function getMinMaxValues($kriteria)
    {
        $nilaiAlternatifModel = new NilaiAlternatifModel();
        $minMax = [];

        foreach ($kriteria as $k) {
            $values = $nilaiAlternatifModel->where('id_kriteria', $k['id_kriteria'])->findColumn('value');
            $minMax[$k['id_kriteria']] = [
                'min' => min($values),
                'max' => max($values)
            ];
        }

        return $minMax;
    }

    private function calculateNormalization($alternatif, $kriteria, $minMax)
    {
        $nilaiAlternatifModel = new NilaiAlternatifModel();
        $normalisasi = [];

        foreach ($alternatif as $alt) {
            foreach ($kriteria as $k) {
                $nilai = $nilaiAlternatifModel->where(['id_alternatif' => $alt['id_alternatif'], 'id_kriteria' => $k['id_kriteria']])->first();
                if ($nilai) {
                    $value = $nilai['value'];
                    $normalizedValue = ($k['tipe_kriteria'] == 'benefit') ? $value / $minMax[$k['id_kriteria']]['max'] : $minMax[$k['id_kriteria']]['min'] / $value;
                    $normalisasi[$alt['id_alternatif']][$k['id_kriteria']] = $this->formatValue($normalizedValue);
                } else {
                    $normalisasi[$alt['id_alternatif']][$k['id_kriteria']] = null;
                }
            }
        }

        return $normalisasi;
    }

    private function calculateWeightedNormalization($normalisasi, $kriteria)
    {
        $terbobot = [];

        foreach ($normalisasi as $id_alternatif => $values) {
            foreach ($values as $id_kriteria => $value) {
                $bobot = array_column($kriteria, 'bobot_kriteria', 'id_kriteria')[$id_kriteria];
                $terbobot[$id_alternatif][$id_kriteria] = $this->formatValue($value * $bobot);
            }
        }

        return $terbobot;
    }

    private function calculateScores($normalisasi, $kriteria)
    {
        $scores = [];

        foreach ($normalisasi as $id_alternatif => $values) {
            $total = 0;
            foreach ($values as $id_kriteria => $value) {
                $nilai_w_kriteria = array_column($kriteria, 'nilai_w_kriteria', 'id_kriteria')[$id_kriteria];
                $score = $this->formatValue($value * $nilai_w_kriteria);
                $scores[$id_alternatif][$id_kriteria] = $score;
                $total += $score;
            }
            $scores[$id_alternatif]['total'] = $this->formatValue($total);
        }

        return $scores;
    }

    private function calculateRanking($scores, $alternatif)
    {
        $ranking = [];

        foreach ($scores as $id_alternatif => $values) {
            $index = array_search($id_alternatif, array_column($alternatif, 'id_alternatif'));
            if ($index !== false && isset($alternatif[$index]['nama_alternatif'])) {
                $ranking[] = [
                    'nama_alternatif' => $alternatif[$index]['nama_alternatif'],
                    'total' => $values['total']
                ];
            }
        }

        usort($ranking, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $ranking;
    }

    private function formatValue($value)
    {
        return rtrim(rtrim(number_format($value, 4, '.', ''), '0'), '.');
    }
}
