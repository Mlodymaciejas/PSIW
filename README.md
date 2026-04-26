# Magazyn B2B - Projekt Zaliczeniowy

Aplikacja magazynowa napisana w **PHP 8** z zachowaniem pełnej architektury **MVC**. System korzysta z relacyjnej bazy danych **MySQL**. 

## ⚙️ Co zawiera projekt?
* **Architektura MVC:** Rozdzielenie widoków (HTML) od logiki (Kontroler) i bazy danych (Model).
* **Pełny CRUD:** Możliwość dodawania, wyświetlania i usuwania produktów.
* **Relacyjna baza danych (3NF):** Połączenie tabel `produkty` i `kategorie` za pomocą klucza obcego (użycie `LEFT JOIN`).
* **Bezpieczeństwo:** Ochrona przed SQL Injection (PDO), XSS oraz bezpieczne logowanie z szyfrowaniem haseł (Bcrypt).

## 🚀 Jak uruchomić?
1. Pobierz ten projekt i wrzuć go do folderu serwera (np. `C:\xampp\htdocs\projekt`).
2. Wejdź do phpMyAdmin, stwórz bazę o nazwie `firma_db` i zaimportuj do niej plik `baza_danych.sql` (znajdziesz go w plikach projektu).
3. Wejdź w przeglądarce pod adres: `http://localhost/projekt/`.

## 🔑 Dane do logowania (Konto Testowe)
* **Login:** `admin`
* **Hasło:** `password` 
*(lub admin123, jeśli hasło zostało nadpisane skryptem).*
