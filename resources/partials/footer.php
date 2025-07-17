<link rel="stylesheet" href="/parqueadero-system/public/Css/footer.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.footer-parqueadero {
    width: 100%;
    background: #223142;
    color: #fff;
    margin-top: 40px;
    padding: 40px 0 20px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-sizing: border-box;
    z-index: 10;
}
@media (max-width: 900px) {
    .footer-parqueadero {
        padding: 30px 0 10px 0;
    }
}
</style>
<div class="footer-parqueadero">
    <div class="footer-container">
        <div class="footer-row">
            <!-- Sección Legal -->
            <div class="footer-col">
                <div class="footer-section">
                    <h6 class="footer-title">
                        <i class="fas fa-balance-scale"></i>Información Legal
                    </h6>
                    <p><i class="far fa-copyright"></i> <?= date("Y"); ?> - Todos los derechos reservados.</p>
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Aviso: Este sistema es para uso exclusivo de empleados de 3Shape.</p>
                </div>
            </div>

            <!-- Sección Contactos -->
            <div class="footer-col">
                <div class="footer-section">
                    <h6 class="footer-title">
                        <i class="fas fa-address-card"></i>Equipo de Desarrollo
                    </h6>
                    <div class="developer-card">
                        <p class="developer-name"><i class="fas fa-user"></i> Killiam González</p>
                        <p><a href="mailto:Killiam.Cruz@3shape.com"><i class="fas fa-envelope"></i> Email</a></p>
                        <p><a href="https://github.com/killiam19" target="_blank"><i class="fab fa-github"></i> GitHub</a></p>
                        <p><a href="https://www.linkedin.com/in/killiam-gonz%C3%A1lez-cruz-22b708312/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a></p>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <a href="/3Shape_project/app/View/Int_manual.html">
                            <i class="fas fa-book"></i> Manual de Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sección Técnica -->
            <div class="footer-col">
                <div class="footer-section">
                    <h6 class="footer-title">
                        <i class="fas fa-cogs"></i>Detalles Técnicos
                    </h6>
                    <p><i class="fas fa-code-branch"></i> Versión: 6.0.0</p>
                    <p><i class="fas fa-calendar-plus"></i> Creado: <?= date("d/m/Y", filectime($_SERVER['SCRIPT_FILENAME'])); ?></p>
                    <p><i class="fas fa-calendar-check"></i> Actualizado: <?= date("d/m/Y", filemtime($_SERVER['SCRIPT_FILENAME'])); ?></p>
                    
                    <div class="mt-3">
                        <a href="diagnostic.php" class="footer-btn btn-outline">
                            <i class="fas fa-tools"></i> Ejecutar Diagnóstico
                        </a>
                        <?php
                        $current_url = $_SERVER['REQUEST_URI'];
                        $debug_param = (strpos($current_url, '?') !== false) ? '&debug=1' : '?debug=1';
                        $debug_url = $current_url . $debug_param;
                        echo '<a href="' . htmlspecialchars($debug_url) . '" class="footer-btn btn-outline">
                                <i class="fas fa-bug"></i> Debug Mode
                              </a>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>