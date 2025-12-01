document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.grade-input');

    inputs.forEach(input => {
        // Recalcula toda vez que o usuário digitar algo
        input.addEventListener('input', () => {
            atualizarMedia(input);
        });
    });

    function atualizarMedia(inputChanged) {
        // 1. Achar a linha (tr) onde o input está
        const row = inputChanged.closest('tr');
        
        // 2. Pegar os inputs dessa linha específica
        // O seletor busca inputs cujo name contém [p1], [p2] ou [tb]
        const p1Input = row.querySelector('input[name*="[p1]"]');
        const p2Input = row.querySelector('input[name*="[p2]"]');
        const tbInput = row.querySelector('input[name*="[tb]"]');
        
        // 3. Pegar os valores (converte vírgula para ponto e garante float)
        const p1 = parseFloat(p1Input.value.replace(',', '.')) || 0;
        const p2 = parseFloat(p2Input.value.replace(',', '.')) || 0;
        const tb = parseFloat(tbInput.value.replace(',', '.')) || 0;

        // Verifica se todos os campos estão vazios
        const todosVazios = p1Input.value === '' && p2Input.value === '' && tbInput.value === '';

        const cellMedia = row.querySelector('.average-cell');
        const cellStatus = row.querySelector('.status-pill');

        if (todosVazios) {
            cellMedia.textContent = '-';
            cellStatus.textContent = '-';
            cellStatus.className = 'status-pill';
            return;
        }

        // 4. Calcular Média
        const media = (p1 + p2 + tb) / 3;
        const mediaFormatada = media.toFixed(1).replace('.', ',');

        // 5. Atualizar na Tela (Visual)
        cellMedia.textContent = mediaFormatada;

        // 6. Atualizar Status e Cores
        if (media >= 6) {
            cellStatus.textContent = 'Aprovado';
            cellStatus.className = 'status-pill pass';
            cellMedia.style.color = '#10b981'; // Verde
        } else {
            cellStatus.textContent = 'Reprovado';
            cellStatus.className = 'status-pill fail';
            cellMedia.style.color = '#ef4444'; // Vermelho
        }
    }
});