$(function(e) {
	$('#datatable-employee').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax":{
				"url": "{{route('karyawan/index/table_data')}}",
				"dataType": "json",
				"type": "POST"
				},
		"header":{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			},
		"columns": [
			{ "data": "employee_id" },
			{ "data": "enroll_id" },
			{ "data": "employee_id" },
			{ "data": "name" },
			{ "data": "gender" },
			{ "data": "place_of_birth" },
			{ "data": "date_birth" },
			{ "data": "address" },
			{ "data": "aksi" },
		]  
	});
} );