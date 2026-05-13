		const modal = document.getElementById('modalLogos');
		const openModal = document.getElementById('openModal');
		const closeModal = document.querySelector('.close-modal');

		openModal.addEventListener('click', () => {
			modal.style.display = 'flex';
		});

		closeModal.addEventListener('click', () => {
			modal.style.display = 'none';
		});

		modal.addEventListener('click', (e) => {
			if (e.target === modal) modal.style.display = 'none';
		});