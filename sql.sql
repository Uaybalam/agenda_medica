#
MYSQL DUMP BACK UP
mysqldump -h localhost -u jonathan -p health > health-$(date +%F).sql
##LOCK TABLES?
mysqldump --single-transaction --quick --lock-tables=false -u localhost -u jonathan -p health > health-$(date +%F).sql
/**
** ===PATIENTS===
**
** WITHOUT FILES
** WITHOUT ENCOUNTERS 
** WITHOUT APPOINTMENTS	
**/
SELECT
	PT.id,
	PT.name,
	PT.last_name,
	PT.phone
FROM patient as PT
LEFT JOIN encounter as EN on EN.patient_id=PT.id
LEFT JOIN appointment as APP on APP.patient_id=PT.id
LEFT JOIN patient_related_files as PRF on PRF.patient_id=PT.id
LEFT JOIN billing as BILL on BILL.patient_id=PT.id
WHERE EN.id is null and APP.id is null and PRF.id is null and BILL.id is NULL
ORDER BY PT.name;


http://healthservice.test/patient/search/15/1?filters%5Bid%5D=&filters%5Bnames%5D=&filters%5Blast_name%5D=&filters%5Bdate_of_birth%5D=&filters%5Bgender%5D=&sort%5Bname%5D=id&sort%5Btype%5D=desc


select
	encounter_invoice.encounter_id 
from encounter_invoice
inner join encounter on encounter.id=encounter_invoice.encounter_id
inner join checked_out on checked_out.id=encounter.checked_out_id
where encounter_invoice.status=0;


UPDATE encounter_invoice
inner join encounter on encounter.id=encounter_invoice.encounter_id
inner join checked_out on checked_out.id=encounter.checked_out_id
	SET encounter_invoice.status=1
where encounter_invoice.status=0;


#Remove appointments
DELETE FROM appointment where patient=0;
DELETE FROM appointment_event where appointment_id=0;


##
UPDATE patient  
INNER JOIN patient_history_active ON patient_history_active.patient_id=patient.id 
	SET patient.recorded_history_surgeries=patient_history_active.surgeries
where patient_history_active.surgeries!='';

/**
**
**/
INSERT INTO encounter_activity(encounter_id,user_id,comments,date_create, date_last_update)
SELECT 
	encounter.id as encounter_id,
	user.id as user_id,
	'encounter_create' as comments,
	Appt.date,
	Appt.date
FROM appointment_event as Appt
INNER JOIN user ON user.nick_name=Appt.user
INNER JOIN encounter ON encounter.appointment_id=Appt.appointment_id
LEFT JOIN (SELECT encounter_id, count(1) FROM encounter_activity WHERE comments='encounter_create' group by encounter_id)as ACTIVITY  ON ACTIVITY.encounter_id=encounter.id
WHERE Appt.event='vitals_created' and ACTIVITY.encounter_id is null;


/**
**APPOINTMENT WITHOUT STATUS	
**/
SELECT 
	id,
	date_format(date_appointment,'%Y - %m - %d'),
	status
FROM appointment 
WHERE 
	status in (1, 2, 3) and date_format(date_appointment,'%Y%m%d') < date_format(NOW(),'%Y%m%d')


#MERGE PATIENTS
SELECT 
	PT1.id as PT1_ID,
	PT2.id as PT2_ID,
	PT1.name,
	PT1.last_name,
	PT1.date_of_birth,
	DATE_FORMAT(PT1.create_at,'%Y-%m-%d') as PT1_CREATED,
	DATE_FORMAT(PT2.create_at,'%Y-%m-%d') as PT2_CREATED
FROM patient as PT1
INNER JOIN (SELECT id, name, last_name,date_of_birth,create_at FROM patient) as PT2 
	ON PT1.id!=PT2.id 
	and PT1.name=PT2.name 
	and PT1.last_name=PT2.last_name
	and PT1.date_of_birth=PT2.date_of_birth
ORDER BY PT1.name, PT1.last_name
LIMIT 1000;

SELECT 
	COUNT(1) as coincidences,
	name, 
	last_name,
	middle_name,
	date_of_birth
FROM patient
GROUP BY name, last_name, middle_name, date_of_birth
HAVING coincidences>1
ORDER BY coincidences