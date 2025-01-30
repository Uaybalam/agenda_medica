<?php 

include_once __DIR__ . '/PDF_Billing.php';

class PDF_Billing_Lines extends PDF_Billing{
   	
	private $_bord = 0;

	public function setLines()
	{
		$this->SetDrawColor(250,0,0);
		$this->SetTextColor(250,0,0 );
		$this->horizontalLines();
		$this->verticalLines();
		$this->printLables();
		$this->printCheckBox();

		$this->blockServices();
	}

	private function blockServices()
	{
		//A
		$this->Rect(10,177.5, 44, 7.5);
		//B
		$this->Rect(54,177.5, 8, 7.5);
		//C
		$this->Rect(62,177.5, 7, 7.5);
		//D
		$this->Rect(69,177.5, 51, 7.5);
		//E
		$this->Rect(120,177.5, 13, 7.5);
		//F
		$this->Rect(133,177.5, 23, 7.5);
		//G
		$this->Rect(156,177.5, 9, 7.5);
		//H
		$this->Rect(165,177.5, 6.5, 7.5);
		//I
		$this->Rect(171.5,177.5, 7, 7.5);
		//J
		$this->Rect(178.5,177.5, 29.5, 7.5);
	}

	private function printCheckBox()
	{
		//BILLING TYPE
		$this->Rect(10,38.8,3.5,3.5);
		$this->Rect(27,38.8,3.5,3.5);
		$this->Rect(45,38.8,3.5,3.5);
		$this->Rect(68,38.8,3.5,3.5);
		$this->Rect(86,38.8,3.5,3.5);
		$this->Rect(106,38.8,3.5,3.5);
		$this->Rect(121.5,38.8,3.5,3.5);
		//SEX
		$this->Rect(113.5,47,3.5,3.5);
		$this->Rect(126.5,47,3.5,3.5);
		//PATIENT RELATIONSHIP
		$this->Rect(91,55.5,3.5,3.5);
		$this->Rect(103.5,55.5,3.5,3.5);
		$this->Rect(113.5,55.5,3.5,3.5);
		$this->Rect(126.5,55.5,3.5,3.5);
		//CURRENT EMPLOYMENT
		$this->Rect(96,89.5,3.5,3.5);
		$this->Rect(111,89.5,3.5,3.5);
		//SEX 2
		$this->Rect(176,89.5,3.5,3.5);
		$this->Rect(190,89.5,3.5,3.5);
		//AUTOACCIDENT
		$this->Rect(96,98,3.5,3.5);
		$this->Rect(111,98,3.5,3.5);
		//OTHER ACCIDENT
		$this->Rect(96,106,3.5,3.5);
		$this->Rect(111,106,3.5,3.5);
		//BENEFITPLAN
		$this->Rect(139,115,3.5,3.5);
		$this->Rect(152,115,3.5,3.5);
		//OUTSIDELAB
		$this->Rect(139,157,3.5,3.5);
		$this->Rect(152,157,3.5,3.5);
		//SSN EIN
		$this->Rect(50,241.6,3.5,3.5);
		$this->Rect(55,241.6,3.5,3.5);
		//ACCEPT ASSOGM;EMT
		$this->Rect(104,241.6,3.5,3.5);
		$this->Rect(116,241.6,3.5,3.5);
	}

	private function printLables()
	{
		
		$this->SetFontSize(6);
		$border = 0;
		$this->setXY(10,34);
		$this->cell(20, 2, "1. MEDICARE" ,$border);
		$this->cell(20, 2, "MEDICAID" ,$border);
		$this->cell(21, 2, "TRICARE" ,$border);
		$this->cell(18, 2, "CHAMPVA" ,$border);
		$this->cell(20, 2, "GROUP" ,$border);
		$this->cell(15, 2, "FECA" ,$border);
		$this->cell(10, 2, "OTHER" ,$border);
		$this->cell(0, 2, "1a. INSURED'S I.D NUMBER" ,$border);
		$this->setXY(13,38);
		$this->cell(17, 2, "(Medicare#)" ,$border);
		$this->cell(18, 2, "(Medicaid#)" ,$border);
		$this->cell(23, 2, "(ID#/DoD#)" ,$border);
		$this->cell(18, 2, "(Member ID#)" ,$border);
		$this->cell(20, 2, "(ID#)" ,$border);
		$this->cell(16, 2, "(ID#)" ,$border);
		$this->cell(10, 2, "(ID#)" ,$border);
		$this->setXY(89,36);
		$this->cell(20, 2, "HEALTH PLAN" ,$border);
		$this->cell(18, 2, "BLK LUNG" ,$border);
		//line 2
		$this->setXY(10,43);
		$this->cell(72, 2, "2. PATIENT'S NAME (Last Name, First Name, Middle Initial)" ,$border);
		$this->cell(37, 2, "3. PATIENT'S BIRTH DATE" ,$border);
		$this->cell(14, 2, "SEX" ,$border);
		$this->cell(0, 2, "4. INSURED'S NAME (Last Name, First Name, Middle Initial)" ,$border);
		$this->setXY(85.5,45);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(6, 2, "YY" ,$border);
		//line 3
		$this->setXY(10,52);
		$this->cell(72, 2, "5. PATIENT'S ADDRESS (No., Street)" ,$border);
		$this->cell(51, 2, "6. PATIENT RELATIONSHIP TO INSURED" ,$border);
		$this->cell(60, 2, "7. INSURED'S ADDRESS (No., Street)" ,$border);
		$this->setXY(86,56);
		$this->cell(9, 2, "Self" ,$border);
		$this->cell(12, 2, "Spouse" ,$border);
		$this->cell(13, 2, "Child" ,$border);
		$this->cell(7, 2, "Other" ,$border);
		//line 4
		$this->setXY(10,60);
		$this->cell(62, 2, "CITY" ,$border);
		$this->cell(10, 2, "STATE" ,$border);
		$this->cell(51, 2, "8. RESERVED FOR NUCC USE" ,$border);
		$this->cell(60, 2, "CITY" ,$border);
		$this->cell(15, 2, "STATE" ,$border);
		//line 5
		$this->setXY(10,69);
		$this->cell(34, 2, "ZIP CODE" ,$border);
		$this->cell(89, 2, "TELEPHONE (Include Area Code)" ,$border);
		$this->cell(36, 2, "ZIP CODE" ,$border);
		$this->cell(38, 2, "TELEPHONE (Include Area Code)" ,$border);
		//line 6
		$this->setXY(10,77);
		$this->cell(72, 2, "9. OTHER INSURED'S NAME (Last Name, First Name, Middle Initial)" ,$border);
		$this->cell(51, 2, "10. IS PATIENT'S CONFITION RELATED TO:" ,$border);
		$this->cell(0, 2, "11. INSURED'S POLICY GROUP OR FECA NUMBER" ,$border);
		//line 7
		$this->setXY(10,85);
		$this->cell(72, 2, "a. OTHER INSURED'S POLICY OR GROUP NUMBER" ,$border);
		$this->cell(51, 2, "a. EMPLOYMENT? (Current or Previous)" ,$border);
		$this->cell(48, 2, "a. INSURED'S DATE OF BIRTH" ,$border);
		$this->cell(0, 2, "SEX" ,$border);
		$this->setXY(140,87);
		$this->cell(10, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(8, 2, "YY" ,$border);
		$this->setXY(99.5,90.3);
		$this->cell(15, 2, "YES" ,$border);
		$this->cell(15, 2, "NO" ,$border);
		$this->setXY(172,90);
		$this->cell(15, 2, "M" ,$border);
		$this->cell(4, 2, "F" ,$border);
		//line 8
		$this->setXY(10,94);
		
		$this->cell(72, 2, "b. RESERVED FOR NUCC USE" ,$border);
		$this->cell(30, 2, "b. AUTO ACCIDENT?" ,$border);
		$this->setXY(118,95.5);
		$this->cell(15, 2, "PLACE (State)" ,$border);
		$this->setXY(133,95);
		$this->cell(0, 2, "b. OTHER CLAIM ID (Designated by NUCC)" ,$border);
		$this->setXY(99.5,99);
		$this->cell(15, 2, "YES" ,$border);
		$this->cell(15, 2, "NO" ,$border);
		//line 9
		$this->setXY(10,103);
		$this->cell(72, 2, "c. RESERVED FOR NUCC USE" ,$border);
		$this->cell(51, 2, "c. OTHER ACCIDENT?" ,$border);
		$this->cell(0, 2, "c. INSURANCE PLAN NAME OR PROGRAM NAME" ,$border);
		$this->setXY(99.5,107 );
		$this->cell(15, 2, "YES" ,$border);
		$this->cell(15, 2, "NO" ,$border);
		//line 10
		$this->setXY(10,111);
		$this->cell(72, 2, "d. INSURANCE PLAN NAME OR PROGRAM NAME" ,$border);
		$this->cell(51, 2, "10d. CLAIM CODES (Designated by NUCC)" ,$border);
		$this->cell(0, 2, "d. IS THERE ANOTHER HEALTH BENEFIT PLAN" ,$border);
		$this->setXY(142,115.5);
		$this->cell(13.5, 2, "YES" ,$border);
		$this->cell(13, 2, "NO" ,$border);
		$this->cell(0, 2, "if yes, complete items 9, 9a, and 9d" ,$border);
		//line 11
		$this->setXY(10,120);
		$this->SetFont('Arial','B',6);
		$this->cell(123, 2, "READ BACK OF FORM BEFORE COMPLETING & THIS FORM." ,$border,1,"C");
		$this->SetFont('Arial','',6);
		$this->cell(123, 2, "12. PATIENT'S OR AUTORIZED PERSON'S SIGNATURE I authorize the release of any medical or other information necessary", $border);
		$this->setXY(14,124);
		$this->cell(119, 2, "to process this claim. I also request payment of goverment benefits either to myself or to the party who accepts assignment", $border, 1);
		$this->setXY(14,126);
		$this->cell(119, 2, "below.", $border, 1);
		$this->setXY(133,120);
		$this->cell(70, 2, "13. INSURED'S OR AUTHORIZED PERSON SIGNATURE I authorize" ,$border);
		$this->setXY(136.5,122);
		$this->cell(70, 2, "payment of medical benefits to the undersigned physician or supplier for" ,$border);
		$this->setXY(136.5,124);
		$this->cell(70, 2, "services described below" ,$border);
		$this->setXY(14,132);
		$this->cell(78, 2, "SIGNED" ,$border);
		$this->cell(46, 2, "DATE" ,$border);
		$this->cell(60, 2, "SIGNED" ,$border);
		//line 12
		$this->setXY(10,137);
		$border = 0;
		$this->cell(68, 2, "14. DATE OF CURRENT ILLNESS, INJURY, or PREGNANCY (LMP)" ,$border);
		$this->cell(55, 2, "15. OTHER DATE" ,$border);
		$this->cell(0, 2, "16. DATES PATIENT UNABLE TO WORK IN CURRENT OCCUPATION" , $border );
		$this->setXY(12,139);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(70, 2, "YY" ,$border);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(28, 2, "YY" ,$border);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(19, 2, "YY" ,$border);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(0, 2, "YY" ,$border);
		$this->setXY(40,141);
		$this->cell(38, 2, "QUAL." ,$border);
		$this->cell(57, 2, "QUAL." ,$border);
		$this->cell(41, 2, "FROM" ,$border);
		$this->cell(4, 2, "TO" ,$border);
		
		//line 13
		$this->setXY(10,145);
		$this->cell(68, 2, "17.a NAME OF REFERRING PROVIDER OR OTHER SOURCE" ,$border);
		$this->cell(55, 2, "17a." ,$border);
		$this->cell(0, 2, "18. HOSPITALIZATION DATES RELATED TO CURRENT SERVICES" ,$border);
		$this->setXY(78,149);
		$this->cell(55, 2, "17b. NPI" ,$border);
		$this->setXY(144,147);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(19, 2, "YY" ,$border);
		$this->cell(9, 2, "MM" ,$border);
		$this->cell(8, 2, "DD" ,$border);
		$this->cell(0, 2, "YY" ,$border);
		$this->setXY(135,149);
		$this->cell(41, 2, "FROM" ,$border);
		$this->cell(4, 2, "TO" ,$border);

		//line 14
		$this->setXY(10,153.5);
		$this->cell(123, 2, "ADDITIONAL CLAIM INFORMATION (Designated by NUCC)" ,$border);
		$this->cell(42, 2, "OUTSIDE LAB" ,$border);
		$this->cell(0, 2, "$ CHARGES" ,$border);

		//Line 15
		$this->setXY(10,161.5);
		$this->cell(123, 2, "21. DIAGNOSIS OR NATURE OF ILLNESS OR INJURY Relate A-L to service line below 24E" ,$border);
		$this->cell(22, 2, "RESUBMISSION" ,$border);
		$this->setXY(104,162);
		$this->cell(12,2, "ICD Ind.", $border );
		$this->_pr(30, "CODE" , 133 ,163.5);
		$this->_pr(0, "ORIGINAL REF. NO."  );
		$this->_pr(33, "A.", 11, 166.5  );
		$this->_pr(33, "B."  );
		$this->_pr(34, "C."  );
		$this->_pr(4, "D."  );
		$this->_pr(33, "E.", 11, 170.5  );
		$this->_pr(33, "F."  );
		$this->_pr(34, "G."  );
		$this->_pr(4, "H."  );
		$this->_pr(33, "I.", 11, 174.5  );
		$this->_pr(33, "J."  );
		$this->_pr(34, "K."  );
		$this->_pr(4, "L."  );
		$this->_pr(0, "23. PRIOR AUTHORIZATION NUMBER", 133, 170 );
		
		
		
		$this->_pr(11, "24. A." ,10,178 );
		$this->_pr(36, "DATE(S) OF SERVICE"  );
		$this->_pr(7, "B."  );
		$this->_pr(5, "C."  );
		$this->_pr(55, "D. PROCEDURES, SERVICES, OR SUPPLIES"  );
		$this->_pr(16, "E."  );
		$this->_pr(18, "F."  );
		$this->_pr(8, "G."  );
		$this->_pr(7, "H."  );
		$this->_pr(22, "I."  );
		$this->_pr(8, "J."  );
		$this->_pr(24, "From" ,18,180 );
		$this->_pr(11.3, "To" );
		$this->_pr(22, "" );
		$this->_pr(44.5, "(Explain Unusual Circumstances)" );
		$this->_pr(13, "DIAGNOSIS" );

		$this->_pr(7, "DAYS" , 156 ,179.6);
		$this->_pr(7, "OR" , 157 ,181.2);
		$this->_pr(7, "UNITS" , 156 ,182.9);
		$this->SetFontSize(5);
		$this->_pr(6, "EPSDT" , 164 ,179.6);
		$this->_pr(6, "Family" , 164.5 ,181);
		$this->_pr(6, "Plan" , 165 ,182.9);

		$this->_pr(6, "ID." , 172 ,180);
		$this->_pr(6, "QUAL." , 171 ,182);

		$this->_pr(12, "RENDERING" , 182 ,180);
		$this->_pr(12, "PROVIDER ID. #" , 180 ,182);
		$this->SetFontSize(6);
		$this->_pr(6, "MM" , 11 ,182);
		$this->_pr(7, "DD" );
		$this->_pr(7, "YY" );
		$this->_pr(8, "MM" );
		$this->_pr(8, "DD" );
		$this->_pr(7, "YY" );

		$this->SetFontSize(5);
		$this->_pr(10, "PLACE" , 53.5,179.8 );
		$this->_pr(10, "OF" , 55,181.5 );
		$this->_pr(7, "SERVICE",53,182.9 );
		$this->SetFontSize(6);

		$this->_pr(10, "EMG",62,182 );
		$this->_pr(23, "CPT/HCPCS" );
		$this->_pr(25, "MODIFIER" );
		$this->_pr(16, "POINTER" );
		$this->_pr(13, "$ CHARGES" );

		$this->_pr(5, "NPI",173,191.5 );
		$this->_pr(5, "NPI",173,200 );
		$this->_pr(5, "NPI",173,208 );
		$this->_pr(5, "NPI",173,217 );
		$this->_pr(5, "NPI",173,225 );
		$this->_pr(5, "NPI",173,234 );
		$this->_pr(40, "25.FEDERAL TAX I.D. NUMBER",10,238 );
		$this->_pr(15, "SSN  EIN" );
		$this->_pr(37, "26. PATIENT'S ACCOUNT NO." );
		$this->_pr(32, "27. ACCEPT ASSIGNMENT?" );
		$this->_pr(26, "28. TOTAL CHARGE" );
		$this->_pr(25, "29. AMOUNT PAID" );
		$this->SetFontSize(5);
		$this->_pr(22, "30. Rsvd for NUCC Use" );
		$this->SetFontSize(6);
		$this->_pr(22, "31. SIGNATURE OF PHYSICIAN OR SUPPLIER",10,248);
		$this->_pr(22, "INCLUDING DEGREES OR CREDENTIALS",13.5,250);
		$this->_pr(22, "(I certify that the statements on the reverse",13.5,252);
		$this->_pr(22, "apply to this bill and are made a part thereof.)",13.5,254);
		$this->_pr(38, "SIGNED",10,264);
		$this->_pr(12, "DATE");
		$this->_pr(22, "32. SERVICE FACILITY LOCATION INFORMATION",65,248);
		$this->_pr(22, "33. BILLING PROVIDER INFO & PH#",134,248);

		$this->SetDrawColor(250,0,0);
		$this->Rect(10,237, 55, 29);
		$this->Rect(65,237, 68, 29);
		$this->Rect(133,237, 75, 29);
	}

	private function verticalLines()
	{
		

		$this->Line( 10, 266, 10, 32 );
		$this->Line( 208, 266, 208, 32 );
		$this->Line( 133, 185, 133, 32 );
		$this->Line( 82, 110.5, 82, 42.5 );
		
		//services
		$this->Line( 30.6, 185, 30.6, 194 );
		$this->Line( 30.6, 198.5, 30.6, 202.5 );
		$this->Line( 30.6, 207, 30.6, 211 );
		$this->Line( 30.6, 215.5, 30.6, 219.5 );
		$this->Line( 30.6, 224, 30.6, 228 );
		$this->Line( 30.6, 233, 30.6, 237 );
		
		$this->Line( 54, 185, 54, 194 );
		$this->Line( 54, 198.5, 54, 202.5 );
		$this->Line( 54, 207, 54, 211 );
		$this->Line( 54, 215.5, 54, 219.5 );
		$this->Line( 54, 224, 54, 228 );
		$this->Line( 54, 233, 54, 237 );

		$this->Line( 62, 185, 62, 194 );
		$this->Line( 62, 198.5, 62, 202.5 );
		$this->Line( 62, 207, 62, 211 );
		$this->Line( 62, 215.5, 62, 219.5 );
		$this->Line( 62, 224, 62, 228 );
		$this->Line( 62, 233, 62, 237 );

		$this->Line( 69, 185, 69, 194 );
		$this->Line( 69, 198.5, 69, 202.5 );
		$this->Line( 69, 207, 69, 211 );
		$this->Line( 69, 215.5, 69, 219.5 );
		$this->Line( 69, 224, 69, 228 );
		$this->Line( 69, 233, 69, 237 );

		$this->Line( 88, 185, 88, 194 );
		$this->Line( 88, 198.5, 88, 202.5 );
		$this->Line( 88, 207, 88, 211 );
		$this->Line( 88, 215.5, 88, 219.5 );
		$this->Line( 88, 224, 88, 228 );
		$this->Line( 88, 233, 88, 237 );

		$this->Line( 120, 185, 120, 194 );
		$this->Line( 120, 198.5, 120, 202.5 );
		$this->Line( 120, 207, 120, 211 );
		$this->Line( 120, 215.5, 120, 219.5 );
		$this->Line( 120, 224, 120, 228 );
		$this->Line( 120, 233, 120, 237 );

		$this->Line( 133, 185, 133, 194 );
		$this->Line( 133, 198.5, 133, 202.5 );
		$this->Line( 133, 207, 133, 211 );
		$this->Line( 133, 215.5, 133, 219.5 );
		$this->Line( 133, 224, 133, 228 );
		$this->Line( 133, 233, 133, 237 );

		$this->Line( 156, 185, 156, 194 );
		$this->Line( 156, 198.5, 156, 202.5 );
		$this->Line( 156, 207, 156, 211 );
		$this->Line( 156, 215.5, 156, 219.5 );
		$this->Line( 156, 224, 156, 228 );
		$this->Line( 156, 233, 156, 237 );

		$this->Line( 165, 185, 165, 194 );
		$this->Line( 165, 198.5, 165, 202.5 );
		$this->Line( 165, 207, 165, 211 );
		$this->Line( 165, 215.5, 165, 219.5 );
		$this->Line( 165, 224, 165, 228 );
		$this->Line( 165, 233, 165, 237 );

		$this->Rect( 171.5, 185, 7, 52 );
		$this->Rect( 178.5, 185, 29.5, 52 );

		//doted
		$this->dotedServices();
	}

	private function horizontalDoted( $xStart, $finish, $y )
	{
		$xEnd = 0;
		while (true) {
			$xEnd = $xStart+1;
			$this->Line( $xStart , $y , $xEnd , $y);
			if($xEnd >= $finish)
				break;

			$xStart = $xEnd + 0.5;
		}
	}

	private function dotedServices()
	{

		$this->horizontalDoted( 171.5, 207 , 190 );
		$this->horizontalDoted( 171.5, 207 , 198.5 );
		$this->horizontalDoted( 171.5, 207 , 207 );
		$this->horizontalDoted( 171.5, 207 , 215.5 );
		$this->horizontalDoted( 171.5, 207 , 224 );
		$this->horizontalDoted( 171.5, 207 , 232.5 );

		$this->createDoted( 17, 194);
		$this->createDoted( 17, 202.5);
		$this->createDoted( 17, 211);
		$this->createDoted( 17, 219.5);
		$this->createDoted( 17, 228);
		$this->createDoted( 17, 237);

		$this->createDoted( 24, 194);
		$this->createDoted( 24, 202.5);
		$this->createDoted( 24, 211);
		$this->createDoted( 24, 219.5);
		$this->createDoted( 24, 228);
		$this->createDoted( 24, 237);

		$this->createDoted( 39, 194);
		$this->createDoted( 39, 202.5);
		$this->createDoted( 39, 211);
		$this->createDoted( 39, 219.5);
		$this->createDoted( 39, 228);
		$this->createDoted( 39, 237);

		$this->createDoted( 47, 194);
		$this->createDoted( 47, 202.5);
		$this->createDoted( 47, 211);
		$this->createDoted( 47, 219.5);
		$this->createDoted( 47, 228);
		$this->createDoted( 47, 237);

		$this->createDoted( 96, 194);
		$this->createDoted( 96, 202.5);
		$this->createDoted( 96, 211);
		$this->createDoted( 96, 219.5);
		$this->createDoted( 96, 228);
		$this->createDoted( 96, 237);

		$this->createDoted( 103, 194);
		$this->createDoted( 103, 202.5);
		$this->createDoted( 103, 211);
		$this->createDoted( 103, 219.5);
		$this->createDoted( 103, 228);
		$this->createDoted( 103, 237);

		$this->createDoted( 110, 194);
		$this->createDoted( 110, 202.5);
		$this->createDoted( 110, 211);
		$this->createDoted( 110, 219.5);
		$this->createDoted( 110, 228);
		$this->createDoted( 110, 237);

		$this->createDoted( 149, 194);
		$this->createDoted( 149, 202.5);
		$this->createDoted( 149, 211);
		$this->createDoted( 149, 219.5);
		$this->createDoted( 149, 228);
		$this->createDoted( 149, 237);
	}

	private function createDoted( $x, $yEnd )
	{
		
		for( $i=0; $i < 3; $i++)
		{
			$yStart = $yEnd - 1;
			$this->Line( $x , $yStart, $x , $yEnd );
			$yEnd   = $yStart - 0.5;
		}
	}

	private function horizontalLines()
	{
		$this->Line( 208, 32, 10, 32 );
		$this->Line( 208, 42.5, 10, 42.5 );
		$this->Line( 208, 51, 10, 51 );
		$this->Line( 208, 59.9, 10, 59.9 );
		
		$this->Line( 82, 67.5, 10, 67.5 );
		$this->Line( 133, 67.5, 208, 67.5 );
		$this->Line( 208, 76, 10, 76 );
		$this->Line( 82, 84, 10, 84 );
		$this->Line( 133, 84, 208, 84 );
		$this->Line( 82, 93, 10, 93 );
		$this->Line( 133, 94, 208, 94 );
		
		$this->Line( 82, 102, 10, 102 );
		$this->Line( 133, 102, 208, 102 );
		$this->Line( 208, 110.5, 10, 110.5 );
		$this->Line( 208, 119, 10, 119 );
		$this->Line( 208, 136.5, 10, 136.5 );
		$this->Line( 208, 144.5, 10, 144.5 );
		$this->Line( 208, 153, 10, 153 );
		$this->Line( 208, 161, 10, 161 );
		$this->Line( 208, 185, 10, 185 );
		$this->Line( 208, 194, 10, 194 );
		$this->Line( 208, 202.5, 10, 202.5 );
		$this->Line( 208, 211, 10, 211 );
		$this->Line( 208, 219.5, 10, 219.5 );
		$this->Line( 208, 228, 10, 228 );
		$this->Line( 208, 237, 10, 237 );
		$this->Line( 208, 246, 10, 246 );
		$this->Line( 208, 262, 65, 262 );


	}

	private function _pr($width, $text, $x = null, $y  = null)
	{
		if($x)
		{
			$this->setXY($x,$y);
		}

		$this->cell($width,2, $text , $this->_bord );
	}

}