<?php

require_once 'connection/connection.php';
require_once 'respuestas.class.php';

class negocios extends connection{

    private $table = "Negocios";
    private $submissionid = "";
    private $nombre="";
    private $submissionDate = "";
    private $aprovalStatus = "Aproved";
    private $hasDba = '';
    private $dbaName = "";
    private $clasificacionTributaria = "";
    private $quickboxBns = "";
    private $montoFacturado = "";
    private $payment = "";
    private $diaPago = "";
    private $pagoMensual = "";
    private $streetAddres = "";
    private $street1 = "";
    private $street2  = "";
    private $city = "";
    private $zipCode = "";
    private $rubro = "";
    private $creacionSunbiz = "";
    private $clienteDesde = "";
    private $taxId = "";
    private $hasPayroll = "";
    private $payrollDesde = "";
    private $nombre1 = "";
    private $telefono1 = "";
    private $email1 = "";
    private $nombre2 = ""; 
    private $telefono2 = "";
    private $email2 = "";
    private $has3erSocio = "";
    private $nombre3 = ""; 
    private $telefono3 = "";
    private $email3 = "";
    private $has4toSocio = "";
    private $nombre4 = ""; 
    private $telefono4 = "";
    private $email4 = "";
    private $referidoPor = "";



    public function recepcion(){
        $_respuesta = new respuestas;
        if (!isset($_POST['submission_id'])) {
            //die("Invalid submission data!");
            return $_respuesta->error_400();
        }
        
    }

    public function listaNegocios($pagina){
        $inicio = 0;
        $cantidad = 100;
        if($pagina>1){
            $inicio = ($cantidad*($pagina-1)) + 1;
            $cantidad = $cantidad*$pagina;
        }
        $query = "SELECT * FROM '$this->table' LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;

    }
    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if(!isset($datos['submission_id']) || !isset($datos['formID']) || !isset($datos['ip'])){
            return $_respuestas->error_401();
        }else{
            $this->submissionid = $datos['submission_id'];
            if($datos['nombredel57']){
                $this->nombre = $datos['nombredel57'];
            }

            if(isset($datos['typea59'])){
                $this->hasDba = $datos['typea59'];
                if($datos['typea59'] != 'NO'){
                    $this->dbaName = $datos['nombredel58'];
                }
            }

            if(isset($datos['tipode'])){
                $this->clasificacionTributaria = $datos['tipode'];
            }

            if(isset($datos['tieneservicio'])){
                $this->quickboxBns = $datos['tieneservicio'];
                if($datos['tieneservicio'] == 'YES'){
                    $tmpdata = $datos['datosde'];
                    $this->montoFacturado = $tmpdata['field_2'];
                    $this->payment = $tmpdata['field_3'];
                    $this->diaPago = $tmpdata['field_4'];
                    $this->pagoMensual = $tmpdata['field_5'];
                }
            }

            if(isset($datos['direcciondel'])){
                $datosdirecion_tmp = $datos['direcciondel'];
                $this->streetAddres = $datosdirecion_tmp['addr_search'];
                $this->street1 = $datosdirecion_tmp['addr_line1'];
                $this->street2 = $datosdirecion_tmp['addr_line2'];
                $this->city = $datosdirecion_tmp['city'].' - '.$datosdirecion_tmp['state'];
                $this->zipCode = (int)$datosdirecion_tmp['postal'];
            }
            if(isset($datos['typea66'])){
                $rubros = $datos['typea66'];
                $this->rubro = implode(' - ', $rubros);
                
            }
            if(isset($datos['fechade'])){
                $tmpdate = $datos['fechade'];
                $this->creacionSunbiz = "{$tmpdate['year']}/{$tmpdate['month']}/{$tmpdate['day']}";
            }
            if(isset($datos['fechade61'])){
                $tmpdate = $datos['fechade61'];
                $this->clienteDesde = "{$tmpdate['year']}/{$tmpdate['month']}/{$tmpdate['day']}";
            }
            if(isset($datos['number'])){
                $this->taxId = $datos['number'];
            }
            if(isset($datos['tieneun'])){
                $this->hasPayroll = $datos['tieneun'];
                if($datos['tieneun'] != 'NO'){
                    $tmpdate = $datos['clientede'];
                    $this->payrollDesde = "{$tmpdate['year']}/{$tmpdate['month']}/{$tmpdate['day']}";
                }
            }
            if(isset($datos['nombredel4'])){
                $nombre_tmp = $datos['nombredel4'];
                $this->nombre1 = "{$nombre_tmp['first']} {$nombre_tmp['last']}";
            }
            if(isset($datos['telefono'])){
                $this->telefono1 = $datos['telefono']['full'];
            }
            if(isset($datos['email'])){
                $this->email1 = $datos['email'];
            }
            if(isset($datos['nombredel'])){
                $nombre_tmp = $datos['nombredel'];
                $this->nombre2  = "{$nombre_tmp['first']} {$nombre_tmp['last']}";
            }
            if(isset($datos['telefono26'])){
                $this->telefono2 = $datos['telefono26']['full'];
            }
            if(isset($datos['email27'])){
                $this->email2 = $datos['email27'];
            }
            if(isset($datos['tieneun30'])){
                $this->has3erSocio = $datos['tieneun30']=="SI"?"YES":$datos['tieneun30'];
                if($datos['tienenun30'] != "NO"){
                    $nombre_tmp = $datos['nombredel31'];
                    $this->nombre3 = "{$nombre_tmp['first']} {$nombre_tmp['last']}";
                    $this->telefono3 = $datos['telefono34']['full'];
                    $this->email3 = $datos['email35'];
                }
            }
            if(isset($datos['tieneun38'])){
                $this->has4toSocio = $datos['tieneun38']=="SI"?"YES":$datos['tieneun38'];
                if($datos['tieneun38'] != "NO"){
                    $nombre_tmp = $datos['nombredel39'];
                    $this->nombre4 = "{$nombre_tmp['first']} {$nombre_tmp['last']}";
                    $this->telefono4 = $datos['telefono42']['full'];
                    $this->email4 = $datos['email43'];
                }
            }
            if(isset($datos['typea'])){
                $this->referidoPor = $datos['typea'];
            }
            $resp = $this->insertarCliente();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta['result'] = array("clienteId"=>$resp);
                return $respuesta;
            }else{
                return $_respuestas->error_500();
            }
        }

    }

    private function insertarCliente(){
        $this->submissionDate = date("Y/m/d");
        $query = "INSERT INTO $this->table (
        `submission_id`,
        `Nombre del Negocio`,
        `Submission Date`, 
        `Approval Status`,
        `Nombre Ficticio (DBA)`,
        `DBA`,
        `Clasificacion Tributaria`,
        `Quickbox BNS`,
        `Monto Facturado`,
        `Down Payment`,
        `Dia de Pago`,
        `Pago Mensual`,
        `Street Addres`,
        `Street Addres line 1`,
        `Street Addres line 2`,
        `City`,
        `Zip code`,
        `Rubro del Negocio`,
        `Fecha de Creacion (Sunbiz)`,
        `Cliente BNS desde`,
        `EIN - FEIN - TAX ID`,
        `Servicio de Nomina(PAYROLL)`,
        `Payroll activo desde`,
        `Nombre 1`,
        `Telefono 1`,
        `Email 1`,
        `Nombre 2`,
        `Telefono 2`,
        `Email 2`,
        `Tercer Socio`,
        `Nombre 3`,
        `Telefono 3`,
        `Email 3`,
        `Cuarto Socio`,
        `Nombre 4`,
        `Telefono 4`, 
        `Email 4`,
        `Referido por`) 
        VALUES (
        '$this->submissionid', 
        '$this->nombre', 
        '$this->submissionDate', 
        '$this->aprovalStatus', 
        '$this->hasDba', 
        '$this->dbaName', 
        '$this->clasificacionTributaria', 
        '$this->quickboxBns',
        '$this->montoFacturado',
        '$this->payment',
        '$this->diaPago',
        '$this->pagoMensual',
        '$this->streetAddres',
        '$this->street1', 
        '$this->street2', 
        '$this->city', 
        '$this->zipCode', 
        '$this->rubro', 
        '$this->creacionSunbiz', 
        '$this->clienteDesde',   
        '$this->taxId', 
        '$this->hasPayroll', 
        '$this->payrollDesde', 
        '$this->nombre1', 
        '$this->telefono1', 
        '$this->email1', 
        '$this->nombre2', 
        '$this->telefono2', 
        '$this->email2',
        '$this->has3erSocio',  
        '$this->nombre3', 
        '$this->telefono3',
        '$this->email3',
        '$this->has4toSocio',
        '$this->nombre4', 
        '$this->telefono4', 
        '$this->email4', 
        '$this->referidoPor'
        );";
        //echo "<pre> 4 $query </pre>";
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function loadFile($file){
        
    } 
}

?>