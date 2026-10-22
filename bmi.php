<?php
// Lettura e sanitizzazione dei dati inviati via POST
$nome = isset($_POST['nome']) ? trim(htmlspecialchars($_POST['nome'])) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

// Verifica dei dati inseriti (Controllo lato server)
$errore = null;

if (empty($nome) || $peso <= 0 || $altezza_cm <= 0) {
    $errore = "Per favore, compila tutti i campi con valori validi prima di procedere.";
} else {
    // Conversione altezza da cm a metri e calcolo BMI
    $altezza_m = $altezza_cm / 100;
    $bmi = $peso / ($altezza_m * $altezza_m);

    // Determinazione della fascia e della classe CSS corrispondente
    if ($bmi < 18.5) {
        $classe_fascia = "sottopeso";
        $nome_fascia = "Sottopeso";
    } elseif ($bmi <= 24.9) {
        $classe_fascia = "normopeso";
        $nome_fascia = "Normopeso";
    } elseif ($bmi <= 29.9) {
        $classe_fascia = "sovrappeso";
        $nome_fascia = "Sovrappeso";
    } else {
        $classe_fascia = "obesita";
        $nome_fascia = "Obesità";
    }
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
            <!-- Messaggio di errore in caso di dati mancanti o non validi -->
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