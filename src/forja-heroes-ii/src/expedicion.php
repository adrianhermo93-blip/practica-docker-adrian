<?php
/**
 * =====================================================================
 *  expedicion.php · Forja de Héroes II · La party y la mazmorra
 * =====================================================================
 *  0613 DWES · UD3 · Tarea de refuerzo
 *
 *  @author   Adrián Hermida Moreno
 *  @variante B · «Las Minas de Hierro Negro»
 *  @version  UD3
 *
 *  Estructura (como en el paso 09 del caso guiado):
 *   1) strict_types y carga de inc/reglas.php y del fichero de datos
 *   2) CÁLCULOS: todo arriba, sin nada de HTML
 *   3) PRESENTACIÓN: HTML con sintaxis alternativa
 *      (foreach (...): ... endforeach;   if (...): ... else: ... endif;)
 *
 *  Todos los resultados salen de los datos: si se cambia algo en
 *  inc/datos-B.php y se recarga, el informe se recalcula entero.
 */

declare(strict_types=1);

// Primero las reglas (constantes) y luego los datos de la variante,
// porque el nombre del fichero de datos depende de la constante VARIANTE.
require_once __DIR__ . '/inc/reglas.php';
require_once __DIR__ . '/inc/datos-' . VARIANTE . '.php';
// =====================================================================
// R2 + R3 · LA PARTY
// =====================================================================
/*
 * Recorremos la party con clave ($i) para poder guardar los datos
 * calculados DENTRO de la propia matriz ($party[$i][...]). Si solo
 * usáramos $heroe, estaríamos modificando una copia y se perdería.
 */
foreach ($party as $i => $heroe) {
    $nivel   = intdiv($heroe['xp'], XP_POR_NIVEL) + 1;
    $vidaMax = VIDA_BASE + $heroe['constitucion'] * $nivel * MULT_VIDA[$heroe['clase']];
    $pctVida = $heroe['vida'] / $vidaMax * 100;

    // R3 · Daño con match SIN default: si llega una clase que no está
    // contemplada, PHP lanza UnhandledMatchError y nos enteramos del fallo
    // en vez de calcular un daño inventado.
    $danio = match ($heroe['clase']) {
        'Guerrera'  => $heroe['fuerza'] ** 2 / 10 + $nivel * 2,
        'Hechicero' => $heroe['inteligencia'] * 3 + $nivel ** 2 / 4,
        'Pícara'    => $heroe['destreza'] * 2 + $nivel ** 1.5,
        'Clérigo'   => ($heroe['inteligencia'] + $heroe['constitucion']) * 1.5 + $nivel,
    };

    // R3 · Estado con if/elseif/else. El orden importa: primero el caso
    // más grave (K.O.), porque una vez que una condición se cumple ya no
    // se miran las siguientes.
    if ($heroe['vida'] <= 0) {
        $estado = 'K.O.';
    } elseif ($pctVida < 35) {
        $estado = 'En peligro';
    } elseif ($pctVida < 70) {
        $estado = 'Herido';
    } else {
        $estado = 'En pie';
    }

    # R3 · Posición con switch: Guerrera y Clérigo comparten resultado,
    # así que se agrupan los dos case sin break entre ellos.
    switch ($heroe['clase']) {
        case 'Guerrera':
        case 'Clérigo':
            $posicion = 'Primera línea';
            break;
        case 'Pícara':
            $posicion = 'Flanco';
            break;
        case 'Hechicero':
            $posicion = 'Retaguardia';
            break;
        default:
            $posicion = 'Reserva';
    }

    // Barra de vida con for (sin str_repeat): en cada vuelta se añade un
    // bloque lleno o vacío según si todavía no hemos llegado a los llenos.
    $bloquesLlenos = (int) round($pctVida / 100 * BLOQUES_BARRA);
    $barra = '';
    for ($b = 0; $b < BLOQUES_BARRA; $b++) {
        $barra .= ($b < $bloquesLlenos) ? '█' : '░';
    }

    // Guardamos todo en la matriz para usarlo después en totales y en el HTML.
    $party[$i]['nivel']    = $nivel;
    $party[$i]['vidaMax']  = $vidaMax;
    $party[$i]['pctVida']  = $pctVida;
    $party[$i]['danio']    = $danio;
    $party[$i]['poder']    = (int) round($danio * $nivel);
    $party[$i]['estado']   = $estado;
    $party[$i]['posicion'] = $posicion;
    $party[$i]['barra']    = $barra;
}

// Totales de la party: array_column saca una "columna" de la matriz y
// array_sum la suma, así no hace falta otro bucle para cada total.
$vidaParty    = array_sum(array_column($party, 'vida'));
$vidaMaxParty = array_sum(array_column($party, 'vidaMax'));
$poderParty   = array_sum(array_column($party, 'poder'));
$nivelMedio   = array_sum(array_column($party, 'nivel')) / count($party);
$vidaEntrada  = $vidaParty; // la guardamos para el informe (vida al entrar)

// Segundo recorrido: solo cuentan los héroes que siguen en pie. Con
// continue saltamos a los K.O. y pasamos directamente al siguiente.
$danioParty     = 0;
$heroesActivos  = 0;
$lider          = null; // todavía no sabemos quién es: empieza vacío
foreach ($party as $heroe) {
    if ($heroe['vida'] <= 0) {
        continue;
    }
    $danioParty += $heroe['danio'];
    $heroesActivos++;
    if ($lider === null || $heroe['poder'] > $lider['poder']) {
        $lider = $heroe;
    }
}

// in_array con true (comparación estricta) para que no haya conversiones
// raras de tipos. Estas dos variables deciden trampas y descansos.
$clases     = array_column($party, 'clase');
$hayPicara  = in_array('Pícara', $clases, true);
$hayClerigo = in_array('Clérigo', $clases, true);

// =====================================================================
// R4 · LA EXPEDICIÓN (sala a sala)
// =====================================================================
// TODO 4.1  Inicializa: $oro = $oroInicial, $xpGanada = 0, $diario = [],
//           $salasExploradas = 0.
// TODO 4.2  foreach ($salas as $n => $sala)  con un switch sobre $sala['tipo']:
//             'vacía'    → línea en el diario y pasar a la SIGUIENTE SALA
//                          (cuidado: continue dentro de switch... ¿1 o 2?)
//             'trampa'   → si hay Pícara, se evita; si no, resta 'valor' de vida
//             'combate'  → resta intdiv(valor, 4) de vida y suma 'valor' a la XP
//             'tesoro'   → suma 'valor' al oro y, si la sala trae 'objeto',
//                          añádelo al inventario (+1 unidad)
//             'descanso' → cura 'valor' (el doble si hay Clérigo), sin pasar
//                          de $vidaMaxParty (min)
// TODO 4.3  Tras el switch: si $vidaParty <= 0 → vida a 0, línea en el diario
//           y break.
// TODO 4.4  $salasPendientes = salas totales − exploradas.

// =====================================================================
// R5 · EL JEFE
// =====================================================================
// TODO 5.1  $tieneLlave con array_key_exists($jefe['llave'], $inventario).
//           $golpe = daño de la party redondeado a int.
// TODO 5.2  if / elseif / else:
//             party sin vida      → 'Derrota' (no llegó)
//             sin llave           → 'Puerta sellada'
//             en otro caso        → combate con while:
//               mientras jefe > 0 Y party > 0 Y ronda < MAX_RONDAS:
//                 ronda++; el jefe recibe $golpe (mínimo 0);
//                 si el jefe sigue vivo, la party recibe su 'ataque' (mínimo 0);
//                 añade la fila a $rondas[] = ['ronda'=>…, 'vidaJefe'=>…, 'vidaParty'=>…]
//               $resultado con match(true): Victoria · Derrota · Retirada
//               Si es Victoria, suma la recompensa al oro.

// =====================================================================
// R4 (cont.) · INVENTARIO FINAL
// =====================================================================
// TODO 4.5  foreach con clave => valor: $valorInventario (cantidad × precio).
// TODO 4.6  $totalObjetos (array_sum). Copia del inventario ordenada de mayor
//           a menor cantidad CONSERVANDO las claves. $objetoMasAbundante.

// =====================================================================
// R6 · EL MAPA
// =====================================================================
// TODO 6.1  Dos for anidados (filas y columnas, con count) para contar cuántas
//           veces aparece cada carácter: $conteoCeldas[$celda] = (… ?? 0) + 1;
// TODO 6.2  ¿El número de 'S' coincide con count($salas)? → $mapaCoherente
// TODO 6.3  Barra de vida de la party con for.

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expedición · TODO nombre de la mazmorra · Forja de Héroes II</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="insignia">VARIANTE TODO</span>
    <h1>TODO nombre de la mazmorra</h1>
    <p class="subtitulo">Informe de expedición · líder: TODO</p>

    <h2>La party</h2>
    <table>
        <tr><th>Héroe</th><th>Clase</th><th>Nivel</th><th>Vida</th><th>Daño</th><th>Poder</th><th>Posición</th><th>Estado</th></tr>
        <!-- TODO R7: una fila por héroe con foreach: ... endforeach;
             Estado en <td class="peligro"> si es K.O. o En peligro, y en
             <td class="vida"> en otro caso (if: ... else: ... endif;) -->
        <tr><td>TODO</td><td></td><td></td>
            <td><span class="barra mini">TODO barra</span> vida/vidaMax</td>
            <td></td><td></td><td></td><td></td></tr>
    </table>

    <div class="rejilla">
        <div class="stat"><div class="etq">Poder total</div><div class="val">TODO</div></div>
        <div class="stat"><div class="etq">Nivel medio</div><div class="val">TODO (1 decimal)</div></div>
        <div class="stat"><div class="etq">Daño por ronda</div><div class="val">TODO (1 decimal)</div></div>
        <div class="stat"><div class="etq">Héroes activos</div><div class="val">TODO / TODO</div></div>
    </div>

    <h2>Diario de la expedición</h2>
    <ol class="diario">
        <!-- TODO R7: un <li> por línea del diario -->
    </ol>
    <p>Salas exploradas: TODO de TODO
        <!-- TODO: solo si quedan salas sin explorar, mostrar cuántas en .peligro -->
        · XP ganada: TODO</p>

    <h2>El jefe: TODO</h2>
    <p>Vida TODO · ataque TODO · golpe de la party: TODO por ronda</p>
    <!-- TODO R7: la tabla de rondas solo se muestra si hubo combate -->
    <table>
        <tr><th>Ronda</th><th>Vida del jefe</th><th>Vida de la party</th></tr>
    </table>
    <p class="resultado TODO-vida-o-peligro"><strong>TODO resultado</strong> · TODO motivo</p>
    <p class="barra vida">PARTY TODO barra vida/vidaMax</p>

    <h2>Inventario</h2>
    <table>
        <tr><th>Objeto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        <!-- TODO R7: una fila por objeto del inventario ORDENADO -->
    </table>
    <div class="rejilla">
        <div class="stat"><div class="etq">Objetos</div><div class="val">TODO</div></div>
        <div class="stat"><div class="etq">Valor del inventario</div><div class="val">TODO mo</div></div>
        <div class="stat"><div class="etq">Oro final</div><div class="val">TODO mo</div></div>
        <div class="stat"><div class="etq">Más abundante</div><div class="val peq">TODO</div></div>
    </div>

    <h2>Mapa de la mazmorra</h2>
    <!-- TODO R7: tabla con foreach anidados (filas y celdas). Cada <td> lleva
         la clase CSS de LEYENDA_MAPA[$celda][0] y el carácter dentro. -->
    <table class="mapa">
    </table>
    <p><!-- TODO: leyenda con el recuento de cada carácter --></p>
    <!-- TODO: mensaje de coherencia del mapa (if: else: endif;) -->

    <footer>Informe generado el TODO con PHP TODO · cada recarga lo recalcula todo desde cero.</footer>
</main>
</body>
</html>
