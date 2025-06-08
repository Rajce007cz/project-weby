<?php namespace App\Controllers;

use CodeIgniter\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

class Export extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $allTables = $db->listTables();
        $tables = array_filter($allTables, fn($t) => str_starts_with($t, 'f1_'));

        return view('export/export', ['tables' => $tables]);
    }

    public function table(string $table = '')
    {
        if (empty($table)) return 'Nebyl zadán název tabulky.';

        $db = \Config\Database::connect();
        if (!in_array($table, $db->listTables())) return "Tabulka '$table' neexistuje.";

        $query = $db->query("SELECT * FROM `$table`");
        $results = $query->getResultArray();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$table.'.csv"');

        $output = fopen('php://output', 'w');

        if (!empty($results)) {
            fputcsv($output, array_keys($results[0]));
            foreach ($results as $row) fputcsv($output, $row);
        } else {
            fputcsv($output, ['Tabulka je prázdná']);
        }

        fclose($output);
        exit();
    }

    public function pdf(string $table = '')
    {
        if (empty($table)) return 'Nebyl zadán název tabulky.';

        $db = \Config\Database::connect();
        if (!in_array($table, $db->listTables())) return "Tabulka '$table' neexistuje.";

        $query = $db->query("SELECT * FROM `$table`");
        $results = $query->getResultArray();

        $html = '<h3>Export tabulky: <code>' . esc($table) . '</code></h3>';

        if (empty($results)) {
            $html .= '<p>Tabulka je prázdná.</p>';
        } else {
            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            $html .= '<thead><tr>';
            foreach (array_keys($results[0]) as $col) {
                $html .= '<th>' . esc($col) . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            foreach ($results as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . esc($cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream($table . '.pdf', ['Attachment' => true]);
        exit();
    }
}