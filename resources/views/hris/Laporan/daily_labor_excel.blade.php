<div>
    <table>
        <tr><td></td></tr>
    </table>
	<table>
		<tr>
			<td style="font-weight: bold;border:2px solid black;" rowspan="2">&nbsp;Date</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;Wage</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;Overtime</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;Incentive</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;BPJS Kesehatan</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;BPJS Ketenagakerjaan</td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;THR</td>
            <td></td>
			<td style="font-weight: bold;border:2px solid black;" colspan="4">&nbsp;Total Employee Cost</td>
		</tr>
		<tr>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total Wage</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total Overtime</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total Incentive</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total BPJS KS</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total BPJS TK</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total THR</td>
            <td></td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting Production</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Supporting General</td>
			<td style="font-weight: bold;border:2px solid black;">&nbsp;Total Employee Cost</td>
		</tr>
		@foreach ($daily_labor as $val)
		<tr>
			<td style="border:2px solid black;">&nbsp;{{$val->tanggal_berjalan}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->supporting_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->supporting_general}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_wages}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->overtime_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->supporting_production_overtime}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->supporting_general_overtime}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_overtime}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->incentive_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->incentive_supporting_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->incentive_supporting_general}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_insentif}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_ks_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_ks_supporting_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_ks_supporting_general}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_bpjs_ks}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_tk_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_tk_supporting_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->bpjs_tk_supporting_general}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_bpjs_tk}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->thr_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->thr_supporting_production}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->thr_supporting_general}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_thr}}</td>
            <td></td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_employee_production_cost}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_employee_supporting_production_cost}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_employee_supporting_general_cost}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->total_employee_cost}}</td>
		</tr>
		@endforeach
	</table>
</div>