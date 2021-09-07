<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `appointments` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
    $qry2 = $conn->query("SELECT * FROM `patient_meta` where patient_id = '{$patient_id}' ");
    foreach($qry2->fetch_all(MYSQLI_ASSOC) as $row){
        $patient[$row['meta_field']] = $row['meta_value'];
    }
  }
?>
<style>
#uni_modal .modal-content>.modal-footer{
    display:none;
}
#uni_modal .modal-body{
    padding-bottom:0 !important;
}
</style>
<div class="container-fluid">
    <p><b>Fecha de Cita:</b> <?php echo date("F d, Y",strtotime($date_sched))  ?></p>
    <p><b>Nombre Paciente:</b> <?php echo $patient['name'] ?></p>
    <p><b>Género:</b> <?php echo ucwords($patient['gender']) ?></p>
    <p><b>Fecha de Nacimiento:</b> <?php echo date("F d, Y",strtotime($patient['dob'])) ?></p>
    <p><b># de Contacto:</b> <?php echo $patient['contact'] ?></p>
    <p><b>Correo:</b> <?php echo $patient['email'] ?></p>
    <p><b>Dirección:</b> <?php echo $patient['address'] ?></p>
    <p><b>Enfermedad:</b> <?php echo $ailment ?></p>
    <p><b>Estado:</b>
        <?php 
        switch($status){ 
            case(0): 
                echo '<span class="badge badge-primary">Pendiente</span>';
            break; 
            case(1): 
            echo '<span class="badge badge-success">Confirmado</span>';
            break; 
            case(2): 
                echo '<span class="badge badge-danger">Cancelado</span>';
            break; 
            default: 
                echo '<span class="badge badge-secondary">NA</span>';
        }
        ?>
    </p>
</div>
<div class="modal-footer border-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>