document.addEventListener('DOMContentLoaded', function () {
    // Inicialización global
    console.log('✅ web-app.js listo');

    //mostrar manuealmente el preloader
    window.mostrarPreloader = function () {
        const preloader = document.getElementById('preloader');
        if (preloader){
            preloader.classList.remove('d-none');
        }
    }

});
