<?php
class patchController extends mvc {
	public function patchAction() {
		/*
		 * if (USER_ID != 8) {
		 * $this->view->NoViewRender = true;
		 * exit ( 0 );
		 * }
		 */
		if (!empty(USER_ID)) {
			
			$host = "localhost";
			$dbname = "creath6g_csol_asmh";
			
			$username = "dbadmin";
			$password = "dbadmin";
			$port = 3306;
			
			try {
				$thisb = new PDO ( 'mysql:host=' . $host . ';port=' . $port . '; dbname=' . $dbname . ';charset=utf8', $username, $password, array () );
				$thisb->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				print "Error!: " . $e->getMessage () . "<br/>";
				die ();
			}
			
			$qry = "update mis_bill set bill_month = concat (EXTRACT(year FROM bill_date),'-' , EXTRACT(month FROM bill_date) ,'-1');";
			
			$stmt = $thisb->prepare ( $qry );
			$stmt->execute ( $cond );
			
			echo $stmt->execute ( $cond ) == 1 ? 'success' : 'error';
		}
	}
}

		
	
	
	


