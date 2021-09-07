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
    padding-top:0 !important;
}
</style>
<div class="container-fluid">
    <form id="appointment_form" class="py-2">
    <div class="row" id="appointment">
        <div class="col-6" id="frm-field">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <input type="hidden" name="patient_id" value="<?php echo isset($patient_id) ? $patient_id : '' ?>">
                <div class="form-group">
                    <label for="name" class="control-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="name" value="<?php echo isset($patient['name']) ? $patient['name'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($patient['email']) ? $patient['email'] : '' ?>"  required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contacto</label>
                    <input type="text" class="form-control" name="contact" value="<?php echo isset($patient['contact']) ? $patient['contact'] : '' ?>"  required>
                </div>
                <div class="form-group">
                    <label for="gender" class="control-label">Género</label>
                    <select type="text" class="custom-select" name="gender" required>
                    <option <?php echo isset($patient['gender']) && $patient['gender'] == "Male" ? "selected": "" ?>>Masculino</option>
                    <option <?php echo isset($patient['gender']) && $patient['gender'] == "Female" ? "selected": "" ?>>Femenino</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob" class="control-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="dob" value="<?php echo isset($patient['dob']) ? $patient['dob'] : '' ?>"  required>
                </div>
        </div>
        <div class="col-6">
                
                <div class="form-group">
                    <label for="address" class="control-label">Dirección</label>
                    <textarea class="form-control" name="address" rows="3" required><?php echo isset($patient['address']) ? $patient['address'] : '' ?></textarea>
                </div>
            <?php if($_settings->userdata('id') > 0): ?>

            <div class="form-group">
                <label for="ailment" class="control-label">Enfermedad</label>
                <textarea class="form-control" name="ailment" rows="3" required><?php echo isset($ailment)? $ailment : "" ?></textarea>
            </div>
            
            <?php else: ?>
                <input type="hidden" name="ailment" value="">
            <?php endif; ?>
            <div class="form-group">
                <label for="date_sched" class="control-label">Fecha Cita</label>
                <input type="datetime-local" class="form-control" name="date_sched" value="<?php echo isset($date_sched)? date("Y-m-d\TH:i",strtotime($date_sched)) : "" ?>" required>
            </div>
            <?php if($_settings->userdata('id') > 0): ?>
            <div class="form-group">
                <label for="status" class="control-label">Estado Cita</label>
                <select name="status" id="status" class="custom custom-select">
                    <option value="0"<?php echo isset($status) && $status == "0" ? "selected": "" ?>>Pendiente</option>
                    <option value="1"<?php echo isset($status) && $status == "1" ? "selected": "" ?>>Confirmado</option>
                    <option value="2"<?php echo isset($status) && $status == "2" ? "selected": "" ?>>Cancelado</option>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" name="status" value="0">
            <?php endif; ?>
        </div>
        <div class="form-group d-flex justify-content-end w-100 form-group">
            <button class="btn-primary btn">Enviar cita</button>
            <button class="btn-light btn ml-2" type="button" data-dismiss="modal">Cancelar</button>
        </div>
        </form>
    </div>
</div>
<script>
$(function(){
    $('#appointment_form').submit(function(e){
        e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_appointment",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("Ocurrió un error",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
                       location.reload()
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: $('#uni_modal').offset().top }, "fast");
                    }else{
						alert_toast("Ocurrió un error",'error');
                        console.log(resp)
					}
						end_loader();
				}
			})
    })
    $('#uni_modal').on('hidden.bs.modal', function (e) {
        if($('#appointment_form').length <= 0)
            location.reload()
    })
})
</script>