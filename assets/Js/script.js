document.addEventListener('DOMContentLoaded', function () {
    // Verifica si existe el modal en la página
    const modalElement = document.getElementById('imagenModal');
    
    if (modalElement) {
      const imagenes = document.querySelectorAll('.card-img-top');
      const modal = new bootstrap.Modal(modalElement);
      const imagenAmpliada = document.getElementById('imagenAmpliada');
  
      imagenes.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function () {
          imagenAmpliada.src = this.src;
          modal.show();
        });
      });
    }
  });