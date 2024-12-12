function confirmarEliminacion(clienteId) {
    if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        window.location.href = 'removeCliente.php?id=' + clienteId;
    }
}