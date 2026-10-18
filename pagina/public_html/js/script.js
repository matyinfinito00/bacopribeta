document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll para los links de navegación
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 70;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Efecto de scroll en navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-custom');
        if (!navbar) return;
        if (window.scrollY > 50) {
            navbar.style.background = '#f3f4f6';
            navbar.style.boxShadow = '0 5px 20px rgba(0,0,0,0.12)';
            navbar.style.borderBottom = '1px solid rgba(0,0,0,0.06)';
        } else {
            navbar.style.background = '#f3f4f6';
            navbar.style.boxShadow = '0 2px 15px rgba(0,0,0,0.06)';
            navbar.style.borderBottom = '1px solid rgba(0,0,0,0.06)';
        }
    });

    // Gestión de horarios en formularios de instalaciones y servicios
    const horarioGeneralDesde = document.getElementById('horario_general_desde');
    const horarioGeneralHasta = document.getElementById('horario_general_hasta');
    const agregarHorarioBtn = document.getElementById('agregar-horario-adicional');
    const horariosAdicionalesContainer = document.getElementById('horarios-adicionales-container');
    
    if (horarioGeneralDesde && horarioGeneralHasta) {
        const diasMap = {
            'lunes': 'Lunes',
            'martes': 'Martes',
            'miercoles': 'Miércoles',
            'jueves': 'Jueves',
            'viernes': 'Viernes',
            'sabado': 'Sábado',
            'domingo': 'Domingo'
        };
        
        // Inicializar contador con los horarios adicionales existentes
        const horariosExistentes = document.querySelectorAll('.horario-adicional');
        let contadorHorariosAdicionales = horariosExistentes.length;
        
        // Obtener días usados en horarios adicionales
        function getDiasEnAdicionales() {
            const dias = [];
            document.querySelectorAll('.horario-adicional').forEach(horarioAdicional => {
                const diaSelect = horarioAdicional.querySelector('.horario-adicional-dia');
                if (diaSelect && diaSelect.value) {
                    dias.push(diaSelect.value);
                }
            });
            return dias;
        }
        
        // Obtener días seleccionados en horario general
        function getDiasEnGeneral() {
            return Array.from(document.querySelectorAll('.horario-general-dia:checked')).map(cb => cb.value);
        }
        
        // Actualizar disponibilidad de días en selects adicionales
        function actualizarDisponibilidadAdicionales() {
            const diasEnGeneral = getDiasEnGeneral();
            document.querySelectorAll('.horario-adicional-dia').forEach(select => {
                const diaActual = select.value;
                Array.from(select.options).forEach(option => {
                    if (option.value && option.value !== diaActual) {
                        if (diasEnGeneral.includes(option.value)) {
                            option.disabled = true;
                            option.style.color = '#999';
                        } else {
                            option.disabled = false;
                            option.style.color = '';
                        }
                    }
                });
            });
        }
        
        // Actualizar disponibilidad de checkboxes del general
        function actualizarDisponibilidadGeneral() {
            const diasEnAdicionales = getDiasEnAdicionales();
            document.querySelectorAll('.horario-general-dia').forEach(checkbox => {
                if (diasEnAdicionales.includes(checkbox.value)) {
                    checkbox.disabled = true;
                    checkbox.title = 'Este día ya tiene un horario adicional';
                } else {
                    checkbox.disabled = false;
                    checkbox.title = '';
                }
            });
        }
        
        // Sincronizar días entre horario general y adicionales (evitar duplicados)
        function sincronizarHorarios() {
            actualizarDisponibilidadAdicionales();
            actualizarDisponibilidadGeneral();
        }
        
        // Event listeners para horario general
        document.querySelectorAll('.horario-general-dia').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Si se marca un día en general, quitar de adicionales si existe
                if (this.checked) {
                    document.querySelectorAll('.horario-adicional').forEach(horarioAdicional => {
                        const diaSelect = horarioAdicional.querySelector('.horario-adicional-dia');
                        if (diaSelect && diaSelect.value === this.value) {
                            diaSelect.value = '';
                        }
                    });
                }
                sincronizarHorarios();
            });
        });
        
        // Función para agregar horario adicional
        function agregarHorarioAdicional(diaValue = '', desde = '', hasta = '') {
            contadorHorariosAdicionales++;
            const id = 'adicional_' + contadorHorariosAdicionales;
            
            const horarioDiv = document.createElement('div');
            horarioDiv.className = 'horario-adicional mb-3 p-3 border rounded';
            horarioDiv.id = id;
            
            horarioDiv.innerHTML = `
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Día</label>
                        <select class="form-select horario-adicional-dia" name="horarios_adicionales[${contadorHorariosAdicionales}][dia]">
                            <option value="">Seleccionar día</option>
                            ${Object.entries(diasMap).map(([value, label]) => 
                                `<option value="${value}" ${diaValue === value ? 'selected' : ''}>${label}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="time" class="form-control horario-adicional-desde" 
                            name="horarios_adicionales[${contadorHorariosAdicionales}][desde]" 
                            value="${desde}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="time" class="form-control horario-adicional-hasta" 
                            name="horarios_adicionales[${contadorHorariosAdicionales}][hasta]" 
                            value="${hasta}">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 eliminar-horario-adicional">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            horariosAdicionalesContainer.appendChild(horarioDiv);
            
            // Event listeners para el nuevo horario
            const diaSelectNuevo = horarioDiv.querySelector('.horario-adicional-dia');
            diaSelectNuevo.addEventListener('change', function() {
                // Si se selecciona un día en adicional, desmarcarlo del general
                if (this.value) {
                    const checkboxGeneral = document.getElementById('general_dia_' + this.value);
                    if (checkboxGeneral && checkboxGeneral.checked) {
                        checkboxGeneral.checked = false;
                    }
                }
                sincronizarHorarios();
            });
            horarioDiv.querySelector('.eliminar-horario-adicional').addEventListener('click', function() {
                horarioDiv.remove();
                sincronizarHorarios();
            });
            
            // Actualizar disponibilidad del select recién creado
            sincronizarHorarios();
        }
        
        // Agregar event listeners a horarios adicionales existentes (para edición)
        horariosExistentes.forEach(horarioDiv => {
            const diaSelect = horarioDiv.querySelector('.horario-adicional-dia');
            const eliminarBtn = horarioDiv.querySelector('.eliminar-horario-adicional');
            
            if (diaSelect) {
                diaSelect.addEventListener('change', function() {
                    // Si se selecciona un día en adicional, desmarcarlo del general
                    if (this.value) {
                        const checkboxGeneral = document.getElementById('general_dia_' + this.value);
                        if (checkboxGeneral && checkboxGeneral.checked) {
                            checkboxGeneral.checked = false;
                        }
                    }
                    sincronizarHorarios();
                });
            }
            if (eliminarBtn) {
                eliminarBtn.addEventListener('click', function() {
                    horarioDiv.remove();
                    sincronizarHorarios();
                });
            }
        });
        
        // Botón para agregar horario adicional
        if (agregarHorarioBtn) {
            agregarHorarioBtn.addEventListener('click', function() {
                agregarHorarioAdicional();
            });
        }
        
        // Validación antes de enviar
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const desdeGeneral = horarioGeneralDesde.value;
                const hastaGeneral = horarioGeneralHasta.value;
                const diasSeleccionados = Array.from(document.querySelectorAll('.horario-general-dia:checked')).length;
                const horariosAdicionales = document.querySelectorAll('.horario-adicional').length;
                
                if (!desdeGeneral || !hastaGeneral || diasSeleccionados === 0) {
                    if (horariosAdicionales === 0) {
                        e.preventDefault();
                        alert('Debes definir un horario general con al menos un día seleccionado, o agregar horarios adicionales.');
                        return false;
                    }
                }
                
                // Validar horarios adicionales
                let hayIncompletos = false;
                document.querySelectorAll('.horario-adicional').forEach(horarioAdicional => {
                    const diaSelect = horarioAdicional.querySelector('.horario-adicional-dia');
                    const desde = horarioAdicional.querySelector('.horario-adicional-desde');
                    const hasta = horarioAdicional.querySelector('.horario-adicional-hasta');
                    
                    if ((diaSelect && diaSelect.value) || desde.value || hasta.value) {
                        if (!diaSelect.value || !desde.value || !hasta.value) {
                            hayIncompletos = true;
                        }
                    }
                });
                
                if (hayIncompletos) {
                    e.preventDefault();
                    alert('Debes completar todos los campos de los horarios adicionales.');
                    return false;
                }
            });
        });
        
        // Inicializar sincronización de días
        sincronizarHorarios();
    }

    // Validación de campos de teléfono: solo permitir números y caracteres de formato
    function validarTelefono(input) {
        // Permitir: números, +, -, espacios, paréntesis
        const valor = input.value;
        // Remover cualquier carácter que no sea número, +, -, espacio o paréntesis
        const valorLimpio = valor.replace(/[^0-9+\-\s()]/g, '');
        if (valor !== valorLimpio) {
            input.value = valorLimpio;
        }
    }

    // Aplicar validación a todos los campos de teléfono
    document.querySelectorAll('input[name="telefono"], input[id="telefono"]').forEach(input => {
        // Cambiar type a tel para mejor UX en móviles
        input.type = 'tel';
        
        // Validar mientras se escribe
        input.addEventListener('input', function() {
            validarTelefono(this);
        });
        
        // Validar al pegar contenido
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
            const textoLimpio = textoPegado.replace(/[^0-9+\-\s()]/g, '');
            const inicio = this.selectionStart;
            const fin = this.selectionEnd;
            const valorActual = this.value;
            this.value = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
            this.setSelectionRange(inicio + textoLimpio.length, inicio + textoLimpio.length);
        });
    });

    // Gestión del sidebar colapsable en el panel de administración
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar-modern');
    const body = document.querySelector('.admin-panel');
    const docEl = document.documentElement;
    
    if (sidebarToggle && sidebar && body) {
        // Sincronizar estado con localStorage asegurando consistencia con el pre-render
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
            docEl.classList.add('sidebar-is-collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            body.classList.remove('sidebar-collapsed');
            docEl.classList.remove('sidebar-is-collapsed');
        }
        
        sidebarToggle.addEventListener('click', function() {
            const willCollapse = !sidebar.classList.contains('collapsed');
            sidebar.classList.toggle('collapsed', willCollapse);
            body.classList.toggle('sidebar-collapsed', willCollapse);
            docEl.classList.toggle('sidebar-is-collapsed', willCollapse);
            
            // Guardar estado persistente
            localStorage.setItem('sidebarCollapsed', willCollapse ? 'true' : 'false');
        });
    }

    // Reactivar transiciones suaves luego de que el navegador procese el render inicial (evita FOUC / parpadeo)
    requestAnimationFrame(function() {
        requestAnimationFrame(function() {
            document.documentElement.classList.remove('preload-no-transition');
        });
    });

    // Preview de imágenes para formularios de instalaciones con capacidad de eliminar
    const imagenesInput = document.getElementById('imagenes');
    if (imagenesInput) {
        const preview = document.getElementById('imagenes-preview');
        if (preview) {
            // Array para almacenar los archivos seleccionados con su preview
            let imagePreviews = [];
            
            imagenesInput.addEventListener('change', function(e) {
                // Agregar nuevos archivos al array
                Array.from(e.target.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewData = {
                                file: file,
                                preview: e.target.result,
                                id: Date.now() + Math.random()
                            };
                            imagePreviews.push(previewData);
                            renderPreviews();
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
            
            function renderPreviews() {
                preview.innerHTML = '';
                
                imagePreviews.forEach((previewData) => {
                    const container = document.createElement('div');
                    container.className = 'img-preview-container';
                    container.dataset.imageId = previewData.id;
                    
                    const img = document.createElement('img');
                    img.src = previewData.preview;
                    img.className = 'img-preview';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'img-preview-remove-btn';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.title = 'Eliminar imagen';
                    removeBtn.onclick = function() {
                        // Eliminar el preview del array
                        imagePreviews = imagePreviews.filter(p => p.id !== previewData.id);
                        // Actualizar el input file
                        updateFileInput();
                        // Re-renderizar los previews
                        renderPreviews();
                    };
                    
                    container.appendChild(img);
                    container.appendChild(removeBtn);
                    preview.appendChild(container);
                });
            }
            
            function updateFileInput() {
                // Crear un nuevo DataTransfer para actualizar el input file
                const dataTransfer = new DataTransfer();
                imagePreviews.forEach(previewData => {
                    dataTransfer.items.add(previewData.file);
                });
                imagenesInput.files = dataTransfer.files;
            }
        }
    }

    // Modal de imágenes para show de instalaciones
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageUrl = button.getAttribute('data-image-url');
            const modalImage = document.getElementById('modalImage');
            if (modalImage && imageUrl) {
                modalImage.src = imageUrl;
            }
        });
    }

});
