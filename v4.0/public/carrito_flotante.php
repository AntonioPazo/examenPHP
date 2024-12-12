<?php
if (isset($_SESSION['user']) && $_SESSION['role'] === 'user') {
?>
<div id="carrito-flotante" class="carrito-flotante">
    <div id="carrito-icono" class="carrito-icono" aria-label="Ver carrito">🛒</div>
    <div id="carrito-contenido" class="carrito-contenido" aria-live="polite">
        <h3 class="carrito-titulo">Tu Carrito</h3>
        <ul id="carrito-lista" class="carrito-lista"></ul>
        <div class="carrito-footer">
            <p class="carrito-total">Total: <span id="carrito-total">€0.00</span></p>
            <button id="carrito-checkout" class="carrito-checkout">Ir a pagar</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="css/styles.css">
<?php
}
?>

