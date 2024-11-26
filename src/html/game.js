document.getElementById('startGame').addEventListener('click', () => {
    fetch('../php/start_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_user: 1 }) // Ganti dengan ID pengguna sebenarnya
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = `permainan2.html?session_id=${data.session_id}`;
            } else {
                alert('Gagal memulai permainan: ' + data.message);
            }
        });
});
