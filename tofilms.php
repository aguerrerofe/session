<?php
session_start();

include_once 'film.php';

if(isset($_POST['erabiltzailea'])){
    $_SESSION['erabiltzailea'] =$_POST['erabiltzailea'];
}
    $erabiltzailea = $_SESSION['erabiltzailea'] ?? 'Gonbidatua';

if(!isset($_SESSION['filmakAsoziatiboa'])) {
    $pelikulaZerrenda = [
        new Film("Superman", "12345678", "1948", 4),
        new Film("Superman", "87654321", "1978", 5),
        new Film("Batman vs. Superman", "11223344", "2016", 3),
        new Film("Superman & Lois", "55667788", "2021", 4),
        new Film("Inception", "10203040", "2010", 5),
        new Film("Interstellar", "99887766", "2014", 5),
        new Film("The Dark Knight", "44332211", "2008", 5),
        new Film("Pulp Fiction", "12344321", "1994", 4),
        new Film("Matrix", "87655678", "1999", 5),
        new Film("Avatar", "11224488", "2009", 3)
    ];

    $_SESSION['filmakAsoziatiboa']=[];
    foreach ($pelikulaZerrenda as $film) {
        $_SESSION['filmakAsoziatiboa'][$film->getISAN()] = [
            'izena' => $film->getIzena(),
            'urtea' => $film->getUrtea(),
            'puntuazioa' => $film->getPuntuazioa()
        ];
    }
}
$filmakAsoziatiboa = $_SESSION['filmakAsoziatiboa'];

$mezuAvisoa = "";
$bilaketaEmaitzak = [];
$inputIzena = "";
$inputIsan = "";
$inputUrtea = "";
$inputPuntuazioa = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formularioa_bidalita'])) {
    
    if (isset($_POST['izena'])) { 
        $inputIzena = $_POST['izena']; 
        }
    if (isset($_POST['isan'])) { 
        $inputIsan = $_POST['isan']; 
        }
    if (isset($_POST['urtea'])) { 
        $inputUrtea = $_POST['urtea']; 
        }
    if (isset($_POST['puntuazioa'])) { 
        $inputPuntuazioa = $_POST['puntuazioa']; 
        }
    if (empty($inputIzena) && empty($inputIsan)) {
        $mezuAvisoa = "ERROREA: Izena eta ISAN eremuak ezin dira biak hutsik egon.";
    }
    elseif (empty($inputIsan) && !empty($inputIzena)) {
        foreach ($filmakAsoziatiboa as $f) {
            if ($f['izena'] == $inputIzena) {
                $bilaketaEmaitzak[] = $f;
            }
        }
        if (empty($bilaketaEmaitzak)) {
            $mezuAvisoa = "Ez da aurkitu '$inputIzena' izena duen pelikularik zerrendan.";
        }
    }
    elseif (!empty($inputIsan) && empty($inputIzena)) {
        if (isset($filmakAsoziatiboa[$inputIsan])) {
            unset($filmakAsoziatiboa[$inputIsan]);
            $mezuAvisoa = "ISAN $inputIsan duen filmaren erregistroa ezabatu egin da zerrendatik.";
        } else {
            $mezuAvisoa = "ERROREA: Ez da aurkitu $inputIsan ISANa duen filmik ezabatzeko.";
        }
    }
    elseif (isset($filmakAsoziatiboa[$inputIsan])) {
        if (!empty($inputUrtea) && $inputPuntuazioa !== '') {
            $filmakAsoziatiboa[$inputIsan]['izena'] = $inputIzena;
            $filmakAsoziatiboa[$inputIsan]['urtea'] = $inputUrtea;
            $filmakAsoziatiboa[$inputIsan]['puntuazioa'] = $inputPuntuazioa;
            $mezuAvisoa = "ISAN $inputIsan duen filmaren datuak eguneratu dira.";
        } else {
            $mezuAvisoa = "ERROREA: Filma eguneratzeko eremu guztiak beteta egon behar dira.";
        }
    }
    else {
        if (strlen($inputIsan) == 8 && !empty($inputUrtea) && $inputPuntuazioa !== '') {
            $filmakAsoziatiboa[$inputIsan] = [
                'izena' => $inputIzena,
                'urtea' => $inputUrtea,
                'puntuazioa' => $inputPuntuazioa
            ];
            $mezuAvisoa = "Film berria ondo gorde da zerrendan!";
        } else {
            $mezuAvisoa = "ERROREA: ISANak 8 digitu izan behar ditu eta eremu guztiek balioak izan behar dituzte.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>TOP Movies - Main</title>
</head>
<body>
    <h1><?= $erabiltzailea ?>REN FILMAK</h1>
    <h3>Filmen Zerrenda</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ISAN</th>
                <th>Izena</th>
                <th>Urtea</th>
                <th>Puntuazioa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filmakAsoziatiboa as $isan => $film): ?>
                <tr>
                    <td><?= $isan ?></td>
                    <td><?= $film['izena'] ?></td>
                    <td><?= $film['urtea'] ?></td>
                    <td><?= $film['puntuazioa'] ?> / 5</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (!empty($mezuAvisoa)): ?>
        <p style="color: red; font-weight: bold;">
            Oharra: <?= $mezuAvisoa ?>
        </p>
    <?php endif; ?>
    <?php if (!empty($bilaketaEmaitzak)): ?>
        <div>
            <h4>Bilaketaren emaitzak:</h4>
            <ul>
                <?php foreach ($bilaketaEmaitzak as $emaitza): ?>
                    <li>"<?= $emaitza['izena'] ?>" <?= $emaitza['urtea'] ?> urtekoa.</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <hr>
    <div>
        <h3>Film Formularioa</h3>
        <form action="tofilms.php" method="POST">
            <input type="hidden" name="formularioa_bidalita" value="1">
            <p>
                <label for="izena">Filmaren Izena:</label><br>
                <input type="text" id="izena" name="izena" value="<?= $inputIzena ?>">
            </p>
            <p>
                <label for="isan">ISAN (8 digitu):</label><br>
                <input type="text" id="isan" name="isan" value="<?= $inputIsan ?>">
            </p>
            <p>
                <label for="urtea">Urtea:</label><br>
                <input type="number" id="urtea" name="urtea" value="<?= $inputUrtea ?>">
            </p>
            <p>
                <label for="puntuazioa">Puntuazioa (0tik 5era):</label><br>
                <select id="puntuazioa" name="puntuazioa">
                    <option value="">--Aukeratu--</option>
                    <?php for ($i = 0; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= ($inputPuntuazioa !== '' && (int)$inputPuntuazioa === $i) ? 'selected' : '' ?>>
                            <?= $i ?>
                    <?php endfor; ?>
                </select>
            </p>
            <button type="submit" action="tofilms.php">Bidali Formularioa</button>
        </form>
    </div>

</body>
</html>