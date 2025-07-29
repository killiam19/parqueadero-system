<?php require resource_path('partials/header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglamento de Parqueaderos 3Shape</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        '3shape-blue': '#bb3d3dff',
                        '3shape-dark': '#003366'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Title Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Reglamento de Uso de Parqueaderos
            </h2>
            <div class="hidden md:block">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Versión 1.0
                    </span>
                </div>
                <br>
            <div class="w-24 h-1 bg-3shape-blue mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Normas claras para garantizar el uso adecuado, seguro y ordenado de los espacios de estacionamiento
            </p>
        </div>

        <!-- Objective Section -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">1. Objetivo</h3>
            </div>
            <p class="text-gray-700 leading-relaxed">
                Establecer normas claras que garanticen el uso adecuado, seguro y ordenado de los espacios de estacionamiento asignados para 3Shape. Se busca garantizar la disponibilidad de espacios, priorizando el acceso y control exclusivos para los empleados lo cual contribuye a reducir el mal uso y promover eficientemente los espacios de parqueo, evitando bloqueos o desperdicio de áreas dando seguridad dentro de las instalaciones.
            </p>
        </section>

        <!-- General Rules Section -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">2. Normas Generales</h3>
            </div>

            <!-- 2.1 Asignación de Espacios -->
            <div class="mb-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2.1</span>
                    Asignación de Espacios de Estacionamiento
                </h4>
                <div class="bg-purple-50 rounded-lg p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-purple-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">Todos los espacios de estacionamiento están asignados exclusivamente a los empleados de 3Shape, sujetos a disponibilidad. Los empleados deben estacionar sus vehículos únicamente en los espacios que hayan sido reservados con anterioridad.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-purple-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">En caso de que se complete el cupo de espacios de estacionamiento reservados no podrás traer vehículo a menos que desees pagar tarifa en parqueadero de visitantes.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-purple-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">Todo vehículo debe estar anteriormente registrado y autorizado por administración de 3Shape. Si no lo está puede incurrir en multas o pagos tarifarios del edificio.</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="font-semibold text-red-800">Importante:</span>
                        </div>
                        <p class="text-red-700 mt-2">Si reservó parqueadero del vehículo y no asistió, tendrá suspensión del servicio por un mes.</p>
                    </div>
                </div>
            </div>

            <!-- 2.2 Zonas de No Estacionamiento -->
            <div class="mb-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2.2</span>
                    Respeto por las Zonas de No Estacionamiento
                </h4>
                <div class="bg-red-50 rounded-lg p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">No se permite estacionar en zonas designadas como áreas de no estacionamiento, como pasillos de acceso, áreas de carga y descarga y zonas marcadas como prohibido estacionar.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">No se permite estacionar en un parqueadero que no esté demarcado como de 3Shape.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">No se permite estacionar en lugares que no están designados para el tipo de vehículo del empleado (excepto motos de alto cilindraje anteriormente registradas y autorizadas).</p>
                    </div>
                </div>
            </div>

            <!-- 2.3 Visitantes -->
            <div class="mb-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2.3</span>
                    Visitantes y/o Proveedores
                </h4>
                <div class="bg-orange-50 rounded-lg p-6">
                    <p class="text-gray-700">3Shape no dispone de espacios de estacionamiento designados para visitantes dentro de sus instalaciones. Por tanto, se solicita a los visitantes y clientes que utilicen los parqueaderos comunales disponibles en el edificio.</p>
                </div>
            </div>

            <!-- 2.4 Carga y Descarga -->
            <div class="mb-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2.4</span>
                    Carga y Descarga
                </h4>
                <div class="bg-yellow-50 rounded-lg p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">Las actividades de carga y descarga deben realizarse en áreas designadas y durante las horas permitidas, sin obstruir el tráfico de vehículos.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-gray-700">Los empleados deben estacionar sus vehículos de manera responsable, evitando bloquear el paso de otros vehículos o áreas de carga y descarga.</p>
                    </div>
                </div>
            </div>

            <!-- 2.6 Seguridad -->
            <div class="mb-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2.6</span>
                    Seguridad del Vehículo
                </h4>
                <div class="bg-green-50 rounded-lg p-6">
                    <p class="text-gray-700">Los empleados son responsables de la seguridad de sus vehículos. Se recomienda el uso de dispositivos de seguridad, como cerraduras o candados, cuando sea necesario y no dejar sus pertenencias a la mano como cascos, guantes, chaquetas, zapatos, maletas, etc.</p>
                </div>
            </div>
        </section>

        <!-- Sanctions Section -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">2.7 Sanciones</h3>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h4 class="font-bold text-red-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mal Parqueo
                    </h4>
                    <p class="text-red-700 text-sm mb-3">En caso de infringir las normas establecidas se aplicará una multa.</p>
                    <div class="bg-red-100 rounded-lg p-3">
                        <p class="font-bold text-red-800">Multa: 3 días de SMLMV</p>
                    </div>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <h4 class="font-bold text-orange-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Daño en Propiedad
                    </h4>
                    <p class="text-orange-700 text-sm">Si el mal uso del estacionamiento resulta en daño a la infraestructura del edificio o vehículos de otros usuarios, el empleado será responsable de cubrir los costos de reparación o reemplazo.</p>
                </div>
            </div>
        </section>

        <!-- Procedure Section -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">2.8 Procedimiento de Sanciones</h3>
            </div>

            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Verificación y Aviso al Infractor</h4>
                        <p class="text-gray-700">Una vez se tenga conocimiento del mal parqueo se informará al propietario del vehículo. El conductor dispone de <span class="font-bold text-blue-600">10 minutos</span> para mover el carro y liberar el garaje afectado.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Cobro de la Multa</h4>
                        <p class="text-gray-700">Se hace efectivo después de los 10 minutos. El infractor deberá cancelar el valor de la multa de <span class="font-bold text-red-600">3 días de SMLMV</span>, que será depositada en la cuenta bancaria de la administración habilitada para este fin.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Message -->
        <div class="text-center py-8">
            <div class="inline-flex items-center space-x-2 bg-3shape-blue text-white px-6 py-3 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span class="font-semibold">Let's change dentistry together</span>
            </div>
        </div>
    </main>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<?php require resource_path('partials/new.footer.php'); ?>