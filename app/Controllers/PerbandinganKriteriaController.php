<?php

namespace App\Controllers;
use App\Models\BobotKriteriaModel;
use App\Models\KriteriaModel;

class PerbandinganKriteriaController extends BaseController
{
    public function index()
    {
        $bobotModel = new BobotKriteriaModel();
        $kriteriaModel = new KriteriaModel();
        $data['bobot_kriteria'] = $bobotModel->findAll();
        $data['kriteria'] = $kriteriaModel->findAll();
        $data['pairwise_matrix'] = $this->getPairwiseComparisonMatrix($data['kriteria'], $data['bobot_kriteria']);
        $data['normalized_matrix'] = $this->getNormalizedMatrix($data['pairwise_matrix']);
        $data['consistency_ratio'] = $this->getConsistencyRatio($data['pairwise_matrix'], $data['normalized_matrix']);
        $data['perbandingan_berpasangan'] = $this->generatePairwiseComparisonTable($data['kriteria'], $data['bobot_kriteria']);
        return view('perbandingan_kriteria_view', $data);
    }

    public function add()
    {
        $model = new BobotKriteriaModel();
        $postData = $this->request->getPost();

        foreach ($postData as $key => $value) {
            list($kriteriaKiri, $kriteriaKanan) = explode('-', $key);
            $isReverse = strpos($value, '1/') === 0 ? 1 : 0;

            if ($isReverse) {
                $value = str_replace('1/', '', $value);
            }

            // Check if the record exists
            $existingRecord = $model->where('id_kriteria_kiri', $kriteriaKiri)
                                    ->where('id_kriteria_kanan', $kriteriaKanan)
                                    ->first();

            $data = [
                'id_kriteria_kiri' => $kriteriaKiri,
                'id_kriteria_kanan' => $kriteriaKanan,
                'value' => $value,
                'is_reverse' => $isReverse
            ];

            if ($existingRecord) {
                // Update the existing record
                $model->update($existingRecord['id_bobot_kriteria'], $data);
            } else {
                // Insert a new record
                $model->insert($data);
            }
        }

        session()->setFlashdata('success', 'Perbandingan kriteria berhasil disimpan.');
        return redirect()->to('/perbandingan-kriteria');
    }

    private function getPairwiseComparisonMatrix($kriteria, $bobot_kriteria)
    {
        $matrix = [];
        $jumlah = [];

        foreach ($kriteria as $i => $kiri) {
            $row = [];
            foreach ($kriteria as $j => $kanan) {
                if ($kiri['id_kriteria'] == $kanan['id_kriteria']) {
                    $value = 1;
                } else {
                    $bobot = array_filter($bobot_kriteria, function ($b) use ($kiri, $kanan) {
                        return ($b['id_kriteria_kiri'] == $kiri['id_kriteria'] && $b['id_kriteria_kanan'] == $kanan['id_kriteria']) ||
                               ($b['id_kriteria_kiri'] == $kanan['id_kriteria'] && $b['id_kriteria_kanan'] == $kiri['id_kriteria']);
                    });
                    $bobot = reset($bobot);
                    if ($bobot) {
                        $value = $bobot['is_reverse'] ? 1 / $bobot['value'] : $bobot['value'];
                    } else {
                        $value = 1;
                    }
                }
                $row[] = $value;
                $jumlah[$j] = ($jumlah[$j] ?? 0) + $value;
            }
            $matrix[] = $row;
        }

        return ['matrix' => $matrix, 'jumlah' => $jumlah];
    }

    private function getNormalizedMatrix($pairwise_matrix)
    {
        $matrix = $pairwise_matrix['matrix'];
        $jumlah = $pairwise_matrix['jumlah'];
        $normalized_matrix = [];

        foreach ($matrix as $i => $row) {
            $normalized_row = [];
            foreach ($row as $j => $value) {
                $normalized_row[] = round($value / $jumlah[$j], 3);
            }
            $normalized_matrix[] = $normalized_row;
        }

        return $normalized_matrix;
    }

    private function formatNumber($number)
    {
        return $number == intval($number) ? intval($number) : number_format($number, 3);
    }

    private function getConsistencyRatio($pairwise_matrix, $normalized_matrix)
    {
        $matrix = $pairwise_matrix['matrix'];
        $eigenvector = array_map(function ($row) {
            return array_sum($row) / count($row);
        }, $normalized_matrix);

        $lambda_max = array_sum(array_map(function ($row, $i) use ($eigenvector) {
            return array_sum(array_map(function ($value, $j) use ($eigenvector) {
                return $value * $eigenvector[$j];
            }, $row, array_keys($row)));
        }, $matrix, array_keys($matrix))) / count($matrix);

        $ci = ($lambda_max - count($matrix)) / (count($matrix) - 1);
        $ri = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $cr = $ci / $ri[count($matrix)];

        return [
            'ci' => $ci,
            'ri' => $ri[count($matrix)],
            'cr' => $cr,
            'is_consistent' => $cr < 0.1
        ];
    }

    private function generatePairwiseComparisonTable($kriteria, $bobot_kriteria)
    {
        $thead = "<thead><tr><th>Kriteria</th>";
        $tbody = "<tbody>";
        $jumlah = [];

        foreach ($kriteria as $i => $kiri) {
            $thead .= "<th class='bg-blue-custom'>{$kiri['nama_kriteria']}</th>";
            $row = "<tr><th class='bg-blue-custom'>{$kiri['nama_kriteria']}</th>";

            foreach ($kriteria as $j => $kanan) {
                if ($kiri['id_kriteria'] == $kanan['id_kriteria']) {
                    $row .= "<td>1</td>";
                    $jumlah[$j] = ($jumlah[$j] ?? 0) + 1;
                    continue;
                }

                $bobot = array_filter($bobot_kriteria, function ($b) use ($kiri, $kanan) {
                    return ($b['id_kriteria_kiri'] == $kiri['id_kriteria'] && $b['id_kriteria_kanan'] == $kanan['id_kriteria']) ||
                           ($b['id_kriteria_kiri'] == $kanan['id_kriteria'] && $b['id_kriteria_kanan'] == $kiri['id_kriteria']);
                });
                $bobot = reset($bobot);
                $value_cell = 1;
                if ($bobot) {
                    $value_cell = $bobot['is_reverse'] ? 1 / $bobot['value'] : $bobot['value'];
                }
                $jumlah[$j] = ($jumlah[$j] ?? 0) + $value_cell;
                $row .= "<td>{$this->formatNumber($value_cell)}</td>";
            }
            $row .= "</tr>";
            $tbody .= $row;
        }

        $thead .= "</tr></thead>";
        $tbody .= "</tbody>";
        $tfoot = "<tfoot><tr><th>Jumlah</th>";
        foreach ($jumlah as $value) {
            $tfoot .= "<th>{$this->formatNumber($value)}</th>";
        }
        $tfoot .= "</tr></tfoot>";

        return $thead . $tbody . $tfoot;
    }
}