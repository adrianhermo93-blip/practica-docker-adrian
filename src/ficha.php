<?php

declare(strict_types=1);
 
require_once __DIR__ . '/inc/heroe.php';

// ---------------------------------------------------------------------
// R1 · Conversión de tipos
// ---------------------------------------------------------------------
 
$fuerza = (int)$fuerzaTxt;
$destreza = (int)$destrezaTxt;
$inteligencia = (int)$inteligenciaTxt;
$constitucion = (int)$constitucionTxt;
 
$experiencia = (int)$experienciaTxt;
$vidaActual = (int)$vidaActualTxt;
 
$oro = (float)$oroTxt;
 
// ---------------------------------------------------------------------
// R2 · Progresión
// ---------------------------------------------------------------------
 
$nivel = intdiv($experiencia, XP_POR_NIVEL) + 1;
 
$xpEnNivel = $experiencia % XP_POR_NIVEL;
 
$xpParaSubir = XP_POR_NIVEL - $xpEnNivel;
 
$pctNivel = $xpEnNivel / XP_POR_NIVEL * 100;
// ---------------------------------------------------------------------
// R3 · Vida
// ---------------------------------------------------------------------
 
$vidaMax = VIDA_BASE + $constitucion * $nivel * MULT_VIDA;
 
$pctVida = $vidaActual / $vidaMax * 100;
// ---------------------------------------------------------------------
// R4 · Combate
// ---------------------------------------------------------------------
 
$danio = $inteligencia * 3 + $nivel ** 2 / 4;
 
$mana = $inteligencia * 10 + ($experiencia % 100);
 
$poder = (int) round($danio * $nivel);
 
$comparacion = $poder <=> PODER_RIVAL;
 
$veredicto = $comparacion === 1
? 'Ventaja: ¡a la carga!'
: ($comparacion === 0
? 'Empate: combate igualado'
: 'Desventaja: mejor retirarse');

$danioFormateado = number_format($danio, 1, ',', '.');
$oroFormateado = number_format($oro, 2, ',', '.');
// ---------------------------------------------------------------------
// R5 · Estado y decisiones
// ---------------------------------------------------------------------

$estado = $pctVida >= 50
? 'Estable'
: 'En peligro';

$puedeAscender = ($nivel >= 5 && $pctVida >= 50)
? 'Sí'
: 'No';

$necesitaPocion = ($pctVida < 40 || !$esVeterano)
? 'Sí'
: 'No';

$cronica = <<<CRONICA
$nombreHeroe es un héroe de nivel $nivel perteneciente a la clase Hechicero.

Actualmente posee $poder puntos de poder y $mana puntos de maná.

Su estado actual es: $estado.

Se prepara para enfrentarse al Dragón de Obsidiana.
CRONICA;

$registro = 'Inicio del combate.';

$registro .= ' Aldric analiza a su rival.';

$registro .= ' Poder propio: ' . $poder . '.';

$registro .= ' Poder rival: ' . PODER_RIVAL . '.';

$registro .= ' Resultado: ' . $veredicto . '.';

$tipoExperienciaAntes = get_debug_type($experienciaTxt);
$tipoExperienciaDespues = get_debug_type($experiencia);

$comparacionDebil = ('8' == 8) ? 'true' : 'false';
$comparacionEstricta = ('8' === 8) ? 'true' : 'false';
// ---------------------------------------------------------------------
// R6 · Barras de progreso
// ---------------------------------------------------------------------

$bloquesVida = (int) round($pctVida / 100 * BLOQUES_BARRA);
$bloquesVidaVacios = BLOQUES_BARRA - $bloquesVida;

$barraVida = str_repeat('█', $bloquesVida)
. str_repeat('░', $bloquesVidaVacios);

$bloquesXp = (int) round($pctNivel / 100 * BLOQUES_BARRA);
$bloquesXpVacios = BLOQUES_BARRA - $bloquesXp;

$barraXp = str_repeat('█', $bloquesXp)
. str_repeat('░', $bloquesXpVacios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de <?= htmlspecialchars($nombreHeroe) ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <?php // Insignia VETERANO/NOVATO con la etiqueta larga y echo ?>
    <?php echo $esVeterano
      ? '<span class="insignia">VETERANO</span>'
      : '<span class="insignia">NOVATO</span>'; ?>
    <?php // A partir de aquí, etiqueta corta de salida ?>
    <h1><?= htmlspecialchars($nombreHeroe) ?></h1>
    <p class="subtitulo">
<?= CLASE_HEROE ?> ·
«<?= $apodo ?? 'Sin apodo' ?>» ·
<?= $lema ?: 'Sin lema (todavía)' ?>
</p>


    <h2>Estadísticas</h2>
    <div class="rejilla">
        <div class="stat">
          <div class="etq">Nivel</div>
          <div class="val"><?= $nivel ?></div>
        </div>
        <div class="stat">
          <div class="etq">Vida</div>
          <div class="val"><?= $vidaActual ?> / <?= $vidaMax ?></div>
        </div>
        <div class="stat">
          <div class="etq">Poder</div>
          <div class="val"><?= $poder ?></div>
        </div>
        <div class="stat">
          <div class="etq">Maná</div>
          <div class="val"><?= $mana ?> pm</div>
        </div>
        <div class="stat">
          <div class="etq">Daño</div>
          <div class="val"><?= $danioFormateado ?></div>
        </div>
        <div class="stat">
          <div class="etq">Oro</div>
          <div class="val"><?= $oroFormateado ?> mo</div>
        </div>
    </div>
    <table>
        <tr>
            <th>Fuerza</th>
            <th>Destreza</th>
            <th>Inteligencia</th>
            <th>Constitución</th>
        </tr>
        <tr>
            <td><?= $fuerza ?></td>
            <td><?= $destreza ?></td>
            <td><?= $inteligencia ?></td>
            <td><?= $constitucion ?></td>
</tr>
    </table>

    <h2>Progreso</h2>
    <p class="barra vida">
       VIDA <?= $barraVida ?> · <?= number_format($pctVida, 1, ',', '.') ?> %
    </p>
    <p class="barra xp">
       XP <?= $barraXp ?> ·
       <?= $xpEnNivel ?>/<?= XP_POR_NIVEL ?>
       (faltan <?= $xpParaSubir ?>)
    </p>

    <h2>Estado y decisiones</h2>
    <table>
        <tr>
           <td>Estado</td>
           <td><?= $estado ?></td>
        </tr>

        <tr>
           <td>¿Puede ascender de rango? (nivel ≥ 5 y vida ≥ 50 %)</td>
           <td><?= $puedeAscender ?></td>
        </tr>

        <tr>
           <td>¿Necesita poción? (vida < 40 % o no veterano)</td>
           <td><?= $necesitaPocion ?></td>
        </tr>

        <tr>
           <td>Rival: <?= NOMBRE_RIVAL ?> (poder <?= PODER_RIVAL ?>)</td>
           <td><?= $comparacion ?> → <?= $veredicto ?></td>
        </tr>

    </table>

    <h2>Crónica</h2>
    <pre><?= $cronica ?></pre>
    <p><em><?= $registro ?></em></p>

    <h2>Modo depuración</h2>
    <pre>
         Experiencia antes de convertir:
         <?= $tipoExperienciaAntes ?>

         Experiencia después de convertir:
         <?= $tipoExperienciaDespues ?>

         Comparación débil ('8' == 8):
         <?= $comparacionDebil ?>

         Comparación estricta ('8' === 8):
         <?= $comparacionEstricta ?>
    </pre>

    <footer>
         <?= date('d/m/Y H:i:s') ?>
        · PHP <?= phpversion() ?>
        · <a href="diagnostico.php">Diagnóstico del servidor</a>
    </footer>
</main>
</body>
</html>
