<?php
// Inizializzazione delle variabili per l'HTML
$errore = null;
$nome = $peso = $altezza_cm = $bmi = $classe_fascia = $nome_fascia = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Lettura e sanitizzazione dei dati POST
    $nome = isset($_POST['nome']) ? trim(htmlspecialchars($_POST['nome'])) : '';
    $peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

    // 2. Controllo validità dei dati
    if (empty($nome) || $peso <= 0 || $altezza_cm <= 0) {
        $errore = "Per favore, compila tutti i campi con valori validi prima di procedere.";
        
        // Assegnazione "NOT_VALID" ai campi per il record CSV
        $log_nome       = empty($nome) ? "NOT_VALID" : $nome;
        $log_peso       = ($peso <= 0) ? "NOT_VALID" : $peso;
        $log_altezza    = ($altezza_cm <= 0) ? "NOT_VALID" : $altezza_cm;
        $log_bmi        = "NOT_VALID";
        $log_fascia     = "NOT_VALID";
    } else {
        // Conversione altezza e calcolo BMI
        $altezza_m = $altezza_cm / 100;
        $bmi = $peso / ($altezza_m * $altezza_m);

        // Determinazione della fascia
        if ($bmi < 18.5) {
            $classe_fascia = "sottopeso";
            $nome_fascia   = "Sottopeso";
        } elseif ($bmi <= 24.9) {
            $classe_fascia = "normopeso";
            $nome_fascia   = "Normopeso";
        } elseif ($bmi <= 29.9) {
            $classe_fascia = "sovrappeso";
            $nome_fascia   = "Sovrappeso";
        } else {
            $classe_fascia = "obesita";
            $nome_fascia   = "Obesità";
        }

        // Valori da registrare nel CSV
        $log_nome       = $nome;
        $log_peso       = $peso;
        $log_altezza    = $altezza_cm;
        $log_bmi        = round($bmi, 2);
        $log_fascia     = $classe_fascia;
    }
    $log = fopen("log.csv", "a");
    $record = date("d/m/Y").",".date("H:i:s").",".$_SERVER["REMOTE_ADDR"].",".$log_nome.",".$log_peso.",".$log_altezza.",".$log_bmi.",".$log_fascia."\n";
    fwrite($log, $record);
    fclose($log);
} else {
    header('Location: form.html');
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="box">
        <h1>Risultato BMI</h1>
        <?php if ($errore): ?>
            <!-- Messaggio di errore in caso di dati non validi -->
            <div class="card obesita">
                <p style="margin: 0; font-weight: bold; color: #B32D2D;">
                    <?php echo $errore; ?>
                </p>
            </div>
        <?php else: ?>
            <!-- Card del risultato visualizzata solo se i dati sono corretti -->
            <div class="card <?php echo $classe_fascia; ?>">
                <p><strong>Nome:</strong> <?php echo $nome; ?></p>
                <p><strong>Dati inseriti:</strong> <?php echo $peso; ?> kg, <?php echo $altezza_cm; ?> cm</p>
                <div class="bmi-valore"><?php echo number_format($bmi, 2, ',', ''); ?></div>
                <div class="fascia"><?php echo $nome_fascia; ?></div>
            </div>
        <?php endif; ?>

        <a href="form.html" class="link-ricalcola">Calcola di nuovo</a>
    </div>
</body>
</html>