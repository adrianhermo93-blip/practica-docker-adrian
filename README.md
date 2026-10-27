# practica-docker-adrian · DWES 2.º DAW

Repositorio del módulo **0613 Desarrollo Web en Entorno Servidor** (2.º DAW · MEDAC).
Contiene **tres ejercicios** que comparten el mismo entorno Docker:

| # | Ejercicio | Unidad | Tipo | Autoría | Dónde está |
|---|---|---|---|---|---|
| 1 | Entorno Docker (Nginx + PHP 8.3 + MySQL) | Práctica inicial | Individual | Adrián Hermida Moreno | Raíz: `docker-compose.yml`, `nginx/`, `php/Dockerfile` |
| 2 | Forja de Héroes · ficha del héroe | UD2 | Individual | Adrián Hermida Moreno | Raíz: `MEMORIA.md`, `capturas/`, `php/forja.ini` y `src/` (salvo `src/forja-heroes-ii/`) |
| 3 | **Forja de Héroes II** · la party y la mazmorra | UD3 | **Grupal** | Adrián Hermida Moreno · Alejandro Segundo Ganoza · Raúl Crespo | [`src/forja-heroes-ii/`](src/forja-heroes-ii/) |

---

## Estructura del repositorio

```text
practica-docker-adrian/
│
├── README.md                     ← este fichero (índice del repositorio)
│
│   ── 1 · ENTORNO DOCKER ──────────────────────────────────────────
├── docker-compose.yml            ← define los contenedores web, php y db
├── nginx/default.conf            ← Nginx: sirve src/ y pasa los .php a PHP-FPM
├── php/Dockerfile                ← imagen PHP 8.3-FPM con pdo_mysql
│
│   ── 2 · UD2 · FORJA DE HÉROES ───────────────────────────────────
├── MEMORIA.md                    ← memoria de la UD2
├── capturas/                     ← capturas de la UD2
├── php/forja.ini                 ← directivas PHP de desarrollo (UD2)
└── src/                          ← raíz web del Docker (http://localhost:8080/)
    ├── ficha.php                 ← UD2 · ficha del héroe
    ├── diagnostico.php           ← UD2 · diagnóstico del entorno
    ├── inc/heroe.php             ← UD2 · reglas y datos del héroe
    ├── css/estilo.css            ← UD2 · estilos
    │
    │   ── 3 · UD3 · FORJA DE HÉROES II (GRUPAL) ──────────────────
    └── forja-heroes-ii/
        ├── MEMORIA.md            ← memoria de la UD3 (con el reparto del trabajo)
        ├── capturas/             ← capturas de la UD3
        └── src/
            ├── expedicion.php    ← informe de expedición (R1-R8)
            ├── css/estilo.css    ← estilos (del kit)
            └── inc/
                ├── reglas.php    ← constantes comunes · VARIANTE = 'B'
                └── datos-A/B/C.php ← datos de cada variante (del kit)
```

---

## Puesta en marcha

Requisito: Docker Desktop.

```bash
docker compose up -d      # arranca los contenedores
docker ps                 # comprueba que web, php y db están en marcha
docker compose down       # los para
```

| Ejercicio | URL |
|---|---|
| UD2 · Ficha del héroe | http://localhost:8080/ficha.php |
| UD2 · Diagnóstico | http://localhost:8080/diagnostico.php |
| UD3 · Informe de expedición | http://localhost:8080/forja-heroes-ii/src/expedicion.php |

---

## 1 · Entorno Docker (práctica inicial)

El objetivo de esta práctica es montar un entorno web utilizando Docker en lugar de herramientas tradicionales como XAMPP. La aplicación se compone de tres contenedores independientes que se comunican mediante Docker Compose:

- **web**: Nginx, recibe las peticiones en el puerto 8080.
- **php**: PHP 8.3-FPM, ejecuta el código de `src/`.
- **db**: MySQL 8.0, almacena la información.

**¿Por qué Docker?** Separar Nginx, PHP y MySQL en contenedores permite trabajar con cada servicio de forma independiente y reproducir el entorno en cualquier equipo con Docker. La principal desventaja es que la configuración inicial es más compleja que con XAMPP.

---

## 2 · UD2 · Forja de Héroes

Generador de la ficha de un héroe (variante B · Aldric «el Arcano»): cálculo de nivel, vida, daño y poder a partir de los datos de `src/inc/heroe.php`.

- Memoria: [`MEMORIA.md`](MEMORIA.md)

---

## 3 · UD3 · Forja de Héroes II (trabajo en grupo)

Informe de expedición de una party de cuatro héroes por una mazmorra (variante B · *Las Minas de Hierro Negro*): estado de la party, diario sala a sala, combate por rondas contra el jefe, inventario final y mapa. Usa `if/elseif`, `switch`, `match`, `for`, `foreach`, `while`, arrays, matrices y sintaxis alternativa en el HTML.

- Código: [`src/forja-heroes-ii/src/expedicion.php`](src/forja-heroes-ii/src/expedicion.php)
- Memoria: [`src/forja-heroes-ii/MEMORIA.md`](src/forja-heroes-ii/MEMORIA.md)

### Reparto del trabajo

| Integrante | Rama | Parte |
|---|---|---|
| Alejandro Segundo Ganoza | `ud3-r2-party` | R2/R3 · cálculos de la party y decisiones de cada héroe |
| Adrián Hermida Moreno | `ud3-r4-expedicion` | R4 · recorrido de las salas e inventario final |
| Alejandro Segundo Ganoza | `ud3-r5-jefe` | R5 · combate contra el jefe |
| Raúl Crespo | `ud3-r6-mapa` | R6 · recuento del mapa y barra final |
| Raúl Crespo | `ud3-r7-presentacion` | R7 · presentación HTML |
| Adrián Hermida Moreno | `ud3-r9-memoria` | R9 · memoria y capturas |

### Ramas del repositorio

| Rama | Contenido |
|---|---|
| `main` | Ejercicios 1 y 2 (Docker y UD2), tal y como se entregaron |
| **`ud3`** | Rama por defecto: ejercicios 1, 2 y 3. Integra todas las ramas `ud3-*` |
| `ud3-r*` | Una rama por requisito de la UD3, fusionada en `ud3` con `--no-ff` |
| `ud3-docs` | Documentación del repositorio y autores |

Flujo seguido: cada parte se desarrolla en su rama a partir de `ud3` y se integra con un *merge* sin *fast-forward*, para que en el historial quede visible qué parte aporta cada rama.

---

*Adrián Hermida Moreno · Alejandro Segundo Ganoza · Raúl Crespo · 2.º DAW · MEDAC*
