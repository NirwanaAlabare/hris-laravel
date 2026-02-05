<?php

namespace App\Services;

class OdbcAttendanceService
{
    protected static function connect()
    {
        return odbc_connect("att_hris", "", "");
    }

    /**
     * Hapus user dari mesin absensi
     *
     * @param string|int $enrollId
     * @param string $field  USERID | Badgenumber | SSN
     */
    public static function deleteEnroll($enrollId, $field = 'USERID')
    {
        $conn = self::connect();

        if (!$conn) {
            return [
                'status' => 'failed',
                'message' => odbc_errormsg()
            ];
        }

        // whitelist field (anti SQL injection)
        $allowedFields = ['USERID', 'Badgenumber', 'SSN'];
        if (!in_array($field, $allowedFields)) {
            return [
                'status' => 'failed',
                'message' => 'Invalid enroll field'
            ];
        }

        $sql = "DELETE FROM USERINFO WHERE {$field} = ?";
        $stmt = odbc_prepare($conn, $sql);

        if (!$stmt) {
            return [
                'status' => 'failed',
                'message' => odbc_errormsg($conn)
            ];
        }

        if (!odbc_execute($stmt, [$enrollId])) {
            return [
                'status' => 'failed',
                'message' => odbc_errormsg($conn)
            ];
        }

        return [
            'status' => 'success',
            'message' => "Enroll {$enrollId} deleted"
        ];
    }
}
