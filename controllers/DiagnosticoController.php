<?php
require_once __DIR__ . '/../config/ia.php';

class DiagnosticoController
{
    private $diagnosticoModel;

    public function __construct()
    {
        $this->diagnosticoModel = new Diagnostico();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/diagnostico/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function analizar()
    {
        $sintoma = $_POST['sintoma'] ?? '';
        if (empty($sintoma)) {
            $_SESSION['error'] = "Debe escribir el síntoma.";
            header("Location: " . BASE_URL . "diagnostico");
            exit;
        }

        // Llamada a Gemini adaptada de cURL
        $payload = [
            "contents" => [["parts" => [["text" => "Actúa como un experto mecánico. Analiza este síntoma en un vehículo y devuelve un JSON estricto con: 'componente_detectado', 'posible_falla', 'prioridad' (Baja/Media/Alta/Urgente), 'especialidad_recomendada' y 'recomendacion'. Síntoma: " . $sintoma]]]],
            "generationConfig" => ["responseMimeType" => "application/json"]
        ];

        $ch = curl_init(GEMINI_ENDPOINT . "?key=" . GEMINI_API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);

        $jsonResponse = json_decode($response, true);
        $respuestaTexto = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        $iaData = json_decode($respuestaTexto, true);

        if (!$iaData) {
            $_SESSION['error'] = "Error al comunicarse con la IA. Intente de nuevo.";
            header("Location: " . BASE_URL . "diagnostico");
            exit;
        }

        $id = $this->diagnosticoModel->create([
            'id_cliente' => null, // Opcional, podría asociarse al usuario logueado
            'marca_vehiculo' => $_POST['marca_vehiculo'] ?? 'Desconocida',
            'modelo_vehiculo' => $_POST['modelo_vehiculo'] ?? 'Desconocido',
            'anio_vehiculo' => $_POST['anio_vehiculo'] ?? null,
            'placa' => $_POST['placa'] ?? null,
            'sintoma' => $sintoma,
            'componente_detectado' => $iaData['componente_detectado'] ?? 'Desconocido',
            'posible_falla' => $iaData['posible_falla'] ?? 'No determinada',
            'prioridad' => $iaData['prioridad'] ?? 'Baja',
            'especialidad_recomendada' => $iaData['especialidad_recomendada'] ?? 'Mecánica General',
            'recomendacion' => $iaData['recomendacion'] ?? '',
            'estado' => 'Analizado'
        ]);

        $_SESSION['success'] = "Análisis IA completado exitosamente.";
        header("Location: " . BASE_URL . "diagnostico/resultado/" . $id);
    }

    public function historial()
    {
        $diagnosticos = $this->diagnosticoModel->getAll();
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/diagnostico/historial.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function resultado($id)
    {
        $diagnostico = $this->diagnosticoModel->find($id);
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/diagnostico/resultado.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
