SELECT
	id, 
	( print_cost + 
	office_visit + 
	laboratories + 
	injections + 
	medications + 
	procedures + 
	physical + 
	ecg +
	ultrasound +
	x_ray  ) as suma,
	subtotal
FROM encounter_invoice;

SELECT
	encounter_id, 
	( print_cost + 
	office_visit + 
	laboratories + 
	injections + 
	medications + 
	procedures + 
	physical + 
	ecg +
	ultrasound +
	x_ray  ) as tmp_subtotal,
	( subtotal + open_balance - discount ) as tmp_total,
	total
FROM encounter_invoice WHERE ( subtotal + open_balance - discount ) != total;

/**
* $this->patient_id    = $encounter->patient_id;
* $this->encounter_id  = $encounter->id;
* $this->discount_type = $patient->discount_type;
*/
INSERT INTO encounter_invoice ( encounter_id, patient_id, discount_type, payment_type )
SELECT 
	encounter.id,
	encounter.patient_id,
	patient.discount_type,
	'Cash' as payment_type
FROM encounter 
INNER JOIN patient ON patient.id=encounter.patient_id
LEFT JOIN encounter_invoice ON encounter_invoice.encounter_id=encounter.id
WHERE encounter.has_insurance = 1 and encounter_invoice.id is null;
/**
* Second query
**/
UPDATE encounter_invoice 
INNER JOIN encounter on encounter.id=encounter_invoice.encounter_id 
	SET encounter_invoice.enabled = 1
WHERE encounter.has_insurance=0 and encounter_invoice.status=1;

/**
* Second query
**/
UPDATE encounter_results 
INNER JOIN encounter on encounter.id=encounter_results.encounter_id 
	SET encounter_results.status = 5
WHERE 
	date_format(encounter.create_at, "%Y%m%d")<20180901
	and encounter_results.status=4;


SELECT COUNT(1) FROM encounter_results 
INNER JOIN encounter on encounter.id=encounter_results.encounter_id 
WHERE 
	date_format(encounter.create_at, "%Y%m%d")<20180901
	and encounter_results.status=4;

/*
* Query For Referrals
*/
UPDATE encounter_referrals
INNER JOIN encounter on encounter.id=encounter_referrals.encounter_id
	SET encounter_referrals.refer_date=date_format(encounter.signed_at,'%m/%d/%Y')
;