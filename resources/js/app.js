import './bootstrap';

// Confirmação nativa, acionável também pelo teclado; sem JavaScript inline.
document.addEventListener('submit', (event) => {
    const message = event.target.dataset.confirm;
    if (message && !window.confirm(message)) event.preventDefault();
});
