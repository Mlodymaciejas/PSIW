<?php
require_once 'Database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Pobiera listę kategorii do selectów
    public function getCategories() {
        return $this->db->query("SELECT * FROM kategorie ORDER BY nazwa ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobiera produkty wraz z nazwą kategorii z innej tabeli
    public function getAll($kategoria_id = '') {
        $sql = "SELECT p.*, k.nazwa as kategoria_nazwa 
                FROM produkty p 
                LEFT JOIN kategorie k ON p.kategoria_id = k.id";
        $params = [];

        if ($kategoria_id != '') {
            $sql .= " WHERE p.kategoria_id = ?";
            $params[] = (int)$kategoria_id;
        }

        $sql .= " ORDER BY p.nazwa ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($nazwa, $cena, $ilosc, $vat, $kategoria_id) {
        $sql = "INSERT INTO produkty (nazwa, cena_netto, ilosc, stawka_vat, kategoria_id) 
                VALUES (:n, :c, :i, :v, :k_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'n' => $nazwa, 'c' => $cena, 'i' => $ilosc, 'v' => $vat, 'k_id' => $kategoria_id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM produkty WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function clearAll() {
        return $this->db->query("DELETE FROM produkty");
    }
}