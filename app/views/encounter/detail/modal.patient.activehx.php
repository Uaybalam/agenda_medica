<?php 



$GLOBALS['mydata'] =  $_['data'];
function printDataActiveHX( $name, $key, $text = false )
{
	$data = $GLOBALS['mydata'];
	
	if(isset($data->{$key}) && $data->{$key} )
	{
		echo '<tr>';
		echo '<td class="col-md-4">'.$name.'</td>';
		if($text)
		{
			echo '<td class="col-md-8">'.$text.'</td></tr>';
		}
		else
		{
			echo '<td class="col-md-8">'.$data->{$key}.'</td></tr>';
		}
	}

}

?>

<table class="table table-hover table-condensed table-bordered"  >
	<tbody>
		<?php 
			printDataActiveHX('Última SHA', 'last_sha'  );
			printDataActiveHX('Última revisión física', 'last_physical'  ); 
			printDataActiveHX('Último PAP', 'pregnancy_last_pap' ,$_['data']->pregnancy_last_pap.' <span class="text-opacity"> Normal : '.$_['data']->last_pap_normal.'</span>');
			printDataActiveHX('Última mamografía', 'pregnancy_last_mamo' ,$_['data']->pregnancy_last_mamo.'  <span class="text-opacity"> Normal : '.$_['data']->last_mamo_normal.'</span>');
			printDataActiveHX('Antígeno Prostático Específico', 'psa'  );
			printDataActiveHX('Colonoscopia 51-75 Yrs', 'last_colonoscopy'  );
			printDataActiveHX('Último SIG', 'last_sig'  );
			printDataActiveHX('Último FOBT', 'last_fobt'  );
			printDataActiveHX('Última chlamidi', 'last_chlamidia'  );
			printDataActiveHX('Último ECG','last_ecg' , $_['data']->last_ecg.' <span class="text-opacity"> Normal: '.$_['data']->last_ecg_normal.'</span>' );
			printDataActiveHX('HGBA1C o Hemoglobina', 'hgba1c_hemoglobin' , $_['data']->hgba1c_hemoglobin.' <span class="text-opacity"> Normal: '.$_['data']->hgba1c_hemoglobin_normal.'</span>' );
			printDataActiveHX('Resultados', 'results' , $_['data']->results.' <span class="text-opacity"> Normal: '.$_['data']->results_normal.'</span>' );

			printDataActiveHX('¿Consume alcohol?', 'alcohol_history'  );
			printDataActiveHX('¿Es fumador?', 'smoking_history'  );
		?>
		<tr >
			<th colspan="2" class="text-center well well-sm" style="text-align:center;"> Embarazos </th>
		</tr>
		<?php
			printDataActiveHX('Control Natal', 'pregnancy_birth_control'  );
			
			
			printDataActiveHX('Embarazos exitosos', 'pregnancy_count_succesfull'  );
			printDataActiveHX('Cesáreas', 'pregnancy_count_cesarean'  );
			printDataActiveHX('Abortos/Abortos espontáneos', 'pregnancy_count_abortions'  );

			$total =  (int)$_['data']->pregnancy_count_succesfull +
			(int)$_['data']->pregnancy_count_cesarean +
			(int)$_['data']->pregnancy_count_abortions;
		
		?>
		<tr >
			<td class="col-md-4" > Total de embarazos </td>
			<td class="col-md-8"><b><?= $total?></b></td>
		</tr>
		<tr >
			<th colspan="2" class="text-center well well-sm" style="text-align:center;"> Vacunas </th>
		</tr>
		<?php
			printDataActiveHX('Última influenza', 'last_influenza'  );
			printDataActiveHX('Vacuna contra el tétanos', 'last_tetanous'  );
			printDataActiveHX('Última vacuna neumocócica', 'vaccine_pneumo'  );
			printDataActiveHX('Vacuna contra el herpes zóster', 'vaccine_zoster'  );
			printDataActiveHX('Último PPD', 'last_ppd' , $_['data']->last_ppd.' <span class="text-opacity"> Normal: '.$_['data']->last_ppd_normal.'</span> ' );
		?>
	</tbody>
</table>