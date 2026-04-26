<?php
require_once 'ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function handle() {
        if (isset($_POST['action']) && $_POST['action'] == 'add') {
            $nazwa = htmlspecialchars($_POST['nazwa']); 
            $cena = filter_var($_POST['cena'], FILTER_VALIDATE_FLOAT);
            $ilosc = filter_var($_POST['ilosc'], FILTER_VALIDATE_INT);
            $vat = filter_var($_POST['vat'], FILTER_VALIDATE_INT);
            $kategoria_id = filter_var($_POST['kategoria_id'], FILTER_VALIDATE_INT);
            
            if (!empty($nazwa) && $cena !== false && $ilosc >= 0 && $kategoria_id) {
                $this->model->add($nazwa, $cena, $ilosc, $vat, $kategoria_id);
                header("Location: index.php"); exit();
            }
        }

        if (isset($_GET['delete'])) {
            $this->model->delete((int)$_GET['delete']);
            header("Location: index.php"); exit();
        }

        if (isset($_GET['clear_all'])) {
            $this->model->clearAll();
            header("Location: index.php"); exit();
        }

        $kat_filter = $_GET['f_kat'] ?? '';
        return $this->model->getAll($kat_filter);
    }

    public function getCategories() {
        return $this->model->getCategories();
    }

    public function getSummary($produkty_list) {
        $netto = 0; $vat = 0; $brutto = 0;
        foreach ($produkty_list as $p) {
            $w_netto = $p['cena_netto'] * $p['ilosc'];
            $netto += $w_netto;
            $vat += $w_netto * ($p['stawka_vat'] / 100);
            $brutto += $w_netto * (1 + $p['stawka_vat'] / 100);
        }
        return ['suma_netto' => $netto, 'suma_vat' => $vat, 'suma_brutto' => $brutto];
    }
}