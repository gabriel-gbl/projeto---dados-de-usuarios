function abrirModal(texto) {
    document.getElementById("modal").style.display = "block";
    document.getElementById("textoCompleto").innerText = texto;
}

function fecharModal() {
    document.getElementById("modal").style.display = "none";
}

window.onclick = function(event) {
    var modal = document.getElementById("modal");
    if (event.target == modal) {
        fecharModal();
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btnVerMais').forEach(function(button) {
        button.addEventListener('click', function() {
            abrirModal(this.getAttribute('data-bio'));
        });
    });
});
