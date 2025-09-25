document.addEventListener('DOMContentLoaded', () => {
  const openButtons = document.querySelectorAll('.ver-mas-btn');
  const closeButtons = document.querySelectorAll('[data-modal-close]');

  openButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(`modal-${btn.dataset.id}`);
      if(modal){
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // deshabilita scroll
      }
    });
  });

  closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = btn.closest('.fixed');
      if(modal){
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto'; // habilita scroll
      }
    });
  });
});


