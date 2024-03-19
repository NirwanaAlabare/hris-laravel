<?php 
require_once 'koneksi.php';

if($_GET['action'] == "table_data"){


		$columns = array( 
	                            0 =>'employee_id', 
	                            1 =>'enroll_id', 
	                            2 =>'employee_id',
	                            3 =>'name',
	                            4 =>'gender',
	                            5=> 'place_of_birth',
	                            6=> 'date_birth',
	                            7=> 'address',
	                        );

		$querycount = $mysqli->query("SELECT count(id) as jumlah FROM employee");
		$datacount = $querycount->fetch_array();
	
  
        $totalData = $datacount['jumlah'];
            
        $totalFiltered = $totalData; 

        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
            
        if(empty($_POST['search']['value']))
        {            
        	$limit = 0;
        	$totalFiltered = 0;
        	$query = $mysqli->query("SELECT enroll_id,employee_id,name,gender,place_of_birth,date_birth,address FROM employee order by $order $dir 
        																LIMIT $limit 
        																OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $mysqli->query("SELECT enroll_id,employee_id,name,gender,place_of_birth,date_birth,address FROM employee WHERE name LIKE '%$search%' or enroll_id LIKE '%$search%' or employee_id LIKE '%$search%' or name LIKE '%$search%' or gender LIKE '%$search%' or place_of_birth LIKE '%$search%' or date_birth LIKE '%$search%' or address LIKE '%$search%'
            															order by $order $dir 
            															LIMIT $limit 
            															OFFSET $start");


           $querycount = $mysqli->query("SELECT count(id) as jumlah FROM employee WHERE name LIKE '%$search%' or enroll_id LIKE '%$search%' or employee_id LIKE '%$search%' or name LIKE 'kusnandar' or gender LIKE '%$search%' or place_of_birth LIKE '%$search%' or date_birth LIKE '%$search%' or address LIKE '%$search%'");
		   $datacount = $querycount->fetch_array();
           $totalFiltered = $datacount['jumlah'];
        }

        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
            while ($r = $query->fetch_array())
            {
                $nestedData['employee_id'] = $r['employee_id'];
                $nestedData['enroll_id'] = $r['enroll_id'];
                $nestedData['employee_id'] = $r['employee_id'];
                $nestedData['name'] = $r['name'];
                $nestedData['gender'] = $r['gender'];
                $nestedData['place_of_birth'] = $r['place_of_birth'];
                $nestedData['date_birth'] = $r['date_birth'];
                $nestedData['address'] = $r['address'];
                $nestedData['aksi'] = "<a href='#' class='btn-warning btn-sm'>Ubah</a>&nbsp; <a href='#' class='btn-danger btn-sm'>Hapus</a>";
                $data[] = $nestedData;
                $no++;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 

}