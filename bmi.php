<?php
// Lettura dei dati dalla pagina precedente
$nome = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

// Conversione altezza da cm a metri e calcolo BMI
$altezza_m = $altezza_cm / 100;
$bmi = 0;

if ($altezza_m > 0) {
    $bmi = $peso / ($altezza_m * $altezza_m);
}
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
        
        <div class="card <?php echo $classe_fascia; ?>">
            <p><strong>Nome:</strong> <?php echo $nome; ?></p>
            <p><strong>Dati inseriti:</strong> <?php echo $peso; ?> kg, <?php echo $altezza_cm; ?> cm</p>
            <div class="bmi-valore"><?php echo number_format($bmi, 2, ',', ''); ?></div>
            <div class="fascia"><?php echo $nome_fascia; ?></div>
        </div>

        <a href="form.html" class="link-ricalcola">Calcola di nuovo</a>
    </div>
</body>
</html>