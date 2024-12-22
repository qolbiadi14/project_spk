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
        $bobotModel = new BobotKriteriaModel();
        $kriteriaModel = new KriteriaModel();
        $postData = $this->request->getPost();

        foreach ($postData as $key => $value) {
            list($kriteriaKiri, $kriteriaKanan) = explode('-', $key);
            $isReverse = strpos($value, '1/') === 0 ? 1 : 0;

            if ($isReverse) {
                $value = str_replace('1/', '', $value);
            }

            // Check if the record exists
            $existingRecord = $bobotModel->where('id_kriteria_kiri', $kriteriaKiri)
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
                $bobotModel->update($existingRecord['id_bobot_kriteria'], $data);
            } else {
                // Insert a new record
                $bobotModel->insert($data);
            }
        }

        // Calculate the normalized matrix and save the W values
        $kriteria = $kriteriaModel->findAll();
        $bobot_kriteria = $bobotModel->findAll();
        $pairwise_matrix = $this->getPairwiseComparisonMatrix($kriteria, $bobot_kriteria);
        $normalized_matrix = $this->getNormalizedMatrix($pairwise_matrix);

        foreach ($normalized_matrix as $i => $row) {
            $w_value = array_sum($row) / count($row);
            $kriteriaModel->update($kriteria[$i]['id_kriteria'], ['nilai_w_kriteria' => $w_value]);
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
                        if ($bobot['id_kriteria_kiri'] == $kiri['id_kriteria']) {
                            $value = $bobot['is_reverse'] ? 1 / $bobot['value'] : $bobot['value'];
                        } else {
                            $value = $bobot['is_reverse'] ? $bobot['value'] : 1 / $bobot['value'];
                        }
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
                $normalized_row[] = round($value / $jumlah[$j], 4);
            }
            $normalized_matrix[] = $normalized_row;
        }

        return $normalized_matrix;
    }

    private function formatNumber($number)
    {
        return $number == intval($number) ? intval($number) : number_format($number, 4, '.', '');
    }

    private function getConsistencyRatio($pairwise_matrix, $normalized_matrix)
    {
        $kriteriaModel = new KriteriaModel();
        $matrix = $pairwise_matrix['matrix'];
        $eigenvector = array_map(function ($row) {
            return array_sum($row) / count($row);
        }, $normalized_matrix);

        $lambda_max = array_sum(array_map(function ($row, $i) use ($eigenvector) {
            return array_sum(array_map(function ($value, $j) use ($eigenvector) {
                return $value * $eigenvector[$j];
            }, $row, array_keys($row)));
        }, $matrix, array_keys($matrix))) / count($matrix);

        $n = $kriteriaModel->countAll();
        $kriteria = $kriteriaModel->findAll();
        $total_sum = array_sum(array_map(function ($row, $i) use ($kriteria) {
            $row_sum = array_sum(array_map(function ($value, $j) use ($kriteria) {
                return $value * $kriteria[$j]['nilai_w_kriteria'];
            }, $row, array_keys($row)));
            return $row_sum / $kriteria[$i]['nilai_w_kriteria'];
        }, $matrix, array_keys($matrix)));
        $t = $total_sum / $n;

        $ci = ($t - $n) / ($n - 1);
        $ri = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $cr = $ci / $ri[$n];

        return [
            'ci' => $ci,
            'ri' => $ri[$n],
            'cr' => $cr,
            'is_consistent' => $cr < 0.1,
            'n' => $n,
            't' => $t
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
                    if ($bobot['id_kriteria_kiri'] == $kiri['id_kriteria']) {
                        $value_cell = $bobot['is_reverse'] ? 1 / $bobot['value'] : $bobot['value'];
                    } else {
                        $value_cell = $bobot['is_reverse'] ? $bobot['value'] : 1 / $bobot['value'];
                    }
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
