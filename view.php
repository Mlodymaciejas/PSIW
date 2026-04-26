<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System B2B - Magazyn Pro (Relacyjny)</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        
        /* Punkt 5: Nagłówek i UX */
        .header-flex { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f3f4; padding-bottom: 15px; margin-bottom: 25px; }
        h1 { color: #1a73e8; margin: 0; font-size: 24px; }
        .user-info { font-size: 14px; color: #5f6368; }
        .logout-link { color: #d93025; text-decoration: none; font-weight: bold; margin-left: 10px; border: 1px solid #d93025; padding: 5px 10px; border-radius: 4px; transition: 0.2s; }
        .logout-link:hover { background: #d93025; color: white; }

        /* Toolbar i Formularze */
        .toolbar { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 15px; border: 1px solid #eef0f2; }
        input, select { padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        
        /* Tabela */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f3f4; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; color: #5f6368; font-size: 14px; }
        td { padding: 14px 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        /* Przyciski */
        .btn { padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; color: white; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s; display: inline-block; }
        .btn-add { background: #1a73e8; }
        .btn-add:hover { background: #1557b0; }
        .btn-del { background: #d93025; padding: 6px 12px; font-size: 12px; }
        .btn-clear { background: #5f6368; }

        /* Punkt 3: Badge dla kategorii */
        .badge-cat { background: #e2e3e5; color: #383d41; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        
        /* Punkt 4: Statusy magazynowe */
        .status { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; margin-top: 5px; }
        .status-ok { background: #e6fffa; color: #234e52; }
        .status-warn { background: #fffaf0; color: #7b341e; }
        .status-crit { background: #fff5f5; color: #822727; }

        .summary-row td { background: #f8f9fa; font-weight: bold; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header-flex">
            <h1>Dashboard Magazynowy</h1>
            <div class="user-info">
                Zalogowany jako: <strong><?= htmlspecialchars($_SESSION['user']) ?></strong>
                <a href="?logout=1" class="logout-link">Wyloguj się</a>
            </div>
        </header>
        
        <main>
            <section class="toolbar">
                <form method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <input type="hidden" name="action" value="add">
                    <input type="text" name="nazwa" placeholder="Nazwa towaru" required style="width: 200px;">
                    <input type="number" step="0.01" name="cena" placeholder="Cena netto" required style="width: 100px;">
                    <input type="number" name="ilosc" value="1" min="0" style="width: 70px;">
                    
                    <select name="kategoria_id" required>
                        <option value="">Wybierz kategorię...</option>
                        <?php foreach ($kategorie as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nazwa']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="vat">
                        <option value="23">23% VAT</option>
                        <option value="8">8% VAT</option>
                        <option value="5">5% VAT</option>
                    </select>
                    
                    <button type="submit" class="btn btn-add">Dodaj Produkt</button>
                </form>

                <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                    <select name="f_kat" onchange="this.form.submit()">
                        <option value="">Wszystkie kategorie</option>
                        <?php foreach ($kategorie as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= (isset($_GET['f_kat']) && $_GET['f_kat'] == $k['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nazwa']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="?clear_all=1" class="btn btn-clear" onclick="return confirm('Czy na pewno usunąć WSZYSTKIE produkty?')">Wyczyść dane</a>
                </form>
            </section>

            <section>
                <table>
                    <thead>
                        <tr>
                            <th>Produkt (A-Z)</th>
                            <th>Kategoria</th>
                            <th>Stan Magazynowy</th>
                            <th>Cena Netto</th>
                            <th>VAT</th>
                            <th>Wartość Brutto</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produkty as $p): 
                            $w_netto = $p['cena_netto'] * $p['ilosc'];
                            $w_brutto = $w_netto * (1 + $p['stawka_vat']/100);

                            if ($p['ilosc'] <= 0) { $s_cl = "status-crit"; $s_tx = "Brak"; }
                            elseif ($p['ilosc'] <= 5) { $s_cl = "status-warn"; $s_tx = "Niski stan"; }
                            else { $s_cl = "status-ok"; $s_tx = "Dostępny"; }
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['nazwa']) ?></strong></td>
                            <td><span class="badge-cat"><?= htmlspecialchars($p['kategoria_nazwa'] ?? 'Brak') ?></span></td>
                            <td>
                                <?= $p['ilosc'] ?> szt. <br>
                                <span class="status <?= $s_cl ?>"><?= $s_tx ?></span>
                            </td>
                            <td><?= number_format($p['cena_netto'], 2, ',', ' ') ?> zł</td>
                            <td><?= $p['stawka_vat'] ?>%</td>
                            <td style="font-weight:bold; color: #1a73e8;"><?= number_format($w_brutto, 2, ',', ' ') ?> zł</td>
                            <td>
                                <a href="?delete=<?= $p['id'] ?>" class="btn btn-del" onclick="return confirm('Usunąć produkt?')">Usuń</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="summary-row">
                            <td colspan="5" style="text-align: right;">ŁĄCZNA WARTOŚĆ BRUTTO (DLA FILTRU):</td>
                            <td colspan="2" style="color: #188038; font-size: 1.2em;"><?= number_format($summary['suma_brutto'], 2, ',', ' ') ?> zł</td>
                        </tr>
                    </tfoot>
                </table>
            </section>
        </main>
    </div>
</body>
</html>