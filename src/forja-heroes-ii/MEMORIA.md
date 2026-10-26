# Memoria · Tarea de refuerzo UD3 «Forja de Héroes II»

**Alumno/a:** TODO · **Variante:** TODO · **Fecha:** TODO

> Esta tarea *lleva nota**: sirve para comprobar que dominas lo que necesitarás en la UD5 (sesiones y login) y para preparar la prueba de diagnóstico del RA 3. Las capturas van en `capturas/` y se enlazan así: `![descripción](capturas/nombre.png)`

## 1. Comprobación de resultados

Compara TU página con la tabla de comprobación del enunciado.

| Dato | Valor esperado (enunciado) | Valor de mi página | ¿Coincide? |
|---|---|---|---|
| Poder total de la party | | | |
| Daño por ronda | | | |
| Líder | | | |
| Vida al salir de las salas | | | |
| Resultado frente al jefe (y ronda) | | | |
| Oro final | | | |
| Valor del inventario | | | |
| Salas en el mapa | | | |

Si algo no coincide, explica qué estaba mal y cómo lo arreglaste.

## 2. Bucles y documento resultante

- Captura del `foreach` que pinta la tabla de la party en tu editor y del **Ctrl+U** de esa tabla.
- En el código hay **una** fila `<tr>`; en el Ctrl+U, ¿cuántas? ¿Por qué?
- ¿Aparece en el Ctrl+U algo de `endforeach` o de `if`? ¿Por qué?

## 3. Experimento: predigo el efecto de un cambio

Añade esta sala **al principio** de `$salas` en tu fichero de datos:

```php
['nombre' => 'Derrumbe', 'tipo' => 'combate', 'valor' => 2400],
```

1. **Predicción** (antes de recargar): ¿cuántas salas se explorarán? ¿Qué resultado tendrá el jefe? ¿Qué dirá el aviso del mapa?
2. **Resultado** real (captura).
3. ¿Qué sentencia ha cortado el recorrido? ¿Qué pasa con la tabla de rondas y por qué?
4. Deja los datos como estaban.

## 4. Experimento: match frente a switch

1. Cambia la clase de un héroe a `'Bardo'` y recarga. Captura del error. ¿Qué tipo de error es y por qué se produce?
2. ¿Qué posición le asigna el `switch` a ese Bardo? ¿Por qué el `switch` no falla y el `match` sí?
3. Deja los datos como estaban. ¿Qué comportamiento prefieres en un programa real y por qué?

## 5. Experimento: continue dentro de switch

1. En el caso `'vacía'`, cambia `continue 2;` por `continue;` y recarga. ¿Qué aviso aparece?
2. ¿Qué cambia en el diario o en el recuento de salas? Explica a qué bucle afecta cada versión.
3. Déjalo como estaba.

## 6. Uso de IA (obligatorio declararlo)

¿Has usado alguna IA? ¿Para qué? Copia una respuesta que te diera y explica si era correcta o qué tuviste que corregir.
