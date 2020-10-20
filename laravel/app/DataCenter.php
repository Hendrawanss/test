<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataCenter extends Model
{
    public function getSensorDashboard() {
        $sql = "SELECT * FROM (
            SELECT CONCAT(a.time_id, ' ', a.time_zone) AS time_id,
            a.datacenter_id,
            a.rak_id,
            a.level_id,
            a.sensor_id,
            a.sensor_val,
            CONCAT( UPPER(a.sensor_id), ' ', a.sensor_val ) AS sensor_detail,
            CASE
                WHEN a.sensor_id = 'temperature'THEN
                    CASE 
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                        CASE
                            WHEN CAST(a.sensor_val AS FLOAT) >= 23.5 AND CAST(a.sensor_val AS FLOAT) < 24.5 THEN 'MINOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 24.5 AND CAST(a.sensor_val AS FLOAT) < 26 THEN 'MAJOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 26 THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    ELSE UPPER(a.sensor_val)
                    END
                WHEN a.sensor_id = 'humidity' THEN
                    CASE 
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                        CASE
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 40 AND CAST(a.sensor_val AS FLOAT) > 35 ) OR ( CAST(a.sensor_val AS FLOAT) >= 60 AND CAST(a.sensor_val AS FLOAT) < 65 ) THEN 'MINOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 35 AND CAST(a.sensor_val AS FLOAT) > 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 65 AND CAST(a.sensor_val AS FLOAT) < 70 ) THEN 'MAJOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 70 ) THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    END
                ELSE UPPER(a.sensor_val)
            END AS severity
            FROM datacenter_schema.tb_sensor a
            JOIN ( SELECT max(time_id) AS time_id, datacenter_id FROM datacenter_schema.tb_sensor GROUP BY datacenter_id ) b
            ON a.datacenter_id = b.datacenter_id AND a.time_id = b.time_id
        ) tempTable
        WHERE severity != 'NORMAL'
        ORDER BY array_position(array['CRITICAL','MAJOR','MINOR','NORMAL'], severity);";
        return DB::select(DB::raw($sql));
    }

    public function getChartHistory() {
        $sql = "SELECT datetime_id as date, 
        SUM( CASE WHEN datacenter_id = 'SUDIANG' THEN total ELSE 0 END ) AS sudiang, 
        SUM( CASE WHEN datacenter_id = 'SUKOHARJO' THEN total ELSE 0 END ) AS sukoharjo,
        SUM( CASE WHEN datacenter_id = 'ARIFIN AHMAD' THEN total ELSE 0 END ) AS arifinahmad, 
        SUM( CASE WHEN datacenter_id = 'TB SIMATUPANG' THEN total ELSE 0 END ) AS tbs,
        SUM( CASE WHEN datacenter_id = 'BSD' THEN total ELSE 0 END ) AS bsd
        FROM datacenter_schema.tb_summary_totalsensor
        WHERE datetime_id >= CURRENT_DATE - INTERVAL '50' DAY
        GROUP BY datetime_id
        ORDER BY datetime_id ASC;";

        return DB::select(DB::raw($sql));
    }

    public function getSensorSummary(){
        $sql = "SELECT tempSeverity.severity,
        SUM( CASE WHEN tempTable.severity IS NOT NULL THEN 1 ELSE 0 END ) AS total
        FROM (
            SELECT CONCAT(a.time_id, ' ', a.time_zone) AS time_id,
                a.datacenter_id,
                a.rak_id,
                a.level_id,
                a.sensor_id,
                a.sensor_val,
                CASE
                    WHEN a.sensor_id = 'temperature'THEN
                        CASE 
                        WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                            CASE
                                WHEN CAST(a.sensor_val AS FLOAT) >= 23.5 AND CAST(a.sensor_val AS FLOAT) < 24.5 THEN 'MINOR'
                                WHEN CAST(a.sensor_val AS FLOAT) >= 24.5 AND CAST(a.sensor_val AS FLOAT) < 26 THEN 'MAJOR'
                                WHEN CAST(a.sensor_val AS FLOAT) >= 26 THEN 'CRITICAL'
                                ELSE 'NORMAL'
                            END
                        ELSE UPPER(a.sensor_val)
                        END
                    WHEN a.sensor_id = 'humidity' THEN
                        CASE 
                        WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                            CASE
                                WHEN ( CAST(a.sensor_val AS FLOAT) <= 40 AND CAST(a.sensor_val AS FLOAT) > 35 ) OR ( CAST(a.sensor_val AS FLOAT) >= 60 AND CAST(a.sensor_val AS FLOAT) < 65 ) THEN 'MINOR'
                                WHEN ( CAST(a.sensor_val AS FLOAT) <= 35 AND CAST(a.sensor_val AS FLOAT) > 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 65 AND CAST(a.sensor_val AS FLOAT) < 70 ) THEN 'MAJOR'
                                WHEN ( CAST(a.sensor_val AS FLOAT) <= 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 70 ) THEN 'CRITICAL'
                                ELSE 'NORMAL'
                            END
                        END
                    ELSE UPPER(a.sensor_val)
                END AS severity
                FROM datacenter_schema.tb_sensor a
                JOIN ( SELECT max(time_id) AS time_id, datacenter_id FROM datacenter_schema.tb_sensor GROUP BY datacenter_id ) b
                ON a.datacenter_id = b.datacenter_id AND a.time_id = b.time_id
        ) tempTable right join (
            SELECT 'MINOR' AS severity
            UNION
            SELECT 'MAJOR' AS severity
            UNION
            SELECT 'CRITICAL' AS severity
            UNION
            SELECT 'NORMAL' AS severity
        ) tempSeverity ON tempTable.severity = tempSeverity.severity
        GROUP BY tempSeverity.severity";

        return DB::select(DB::raw($sql));
    }

    public function getSensorSummaryPerDatacenter($datacenter_name) {
        $sql = "SELECT tempSeverity.severity AS severity,
        SUM( CASE WHEN tempTable.severity IS NOT NULL THEN 1 ELSE 0 END ) AS total
        FROM (
            SELECT CONCAT(a.time_id, ' ', a.time_zone) AS time_id,
            a.datacenter_id,
            a.rak_id,
            a.level_id,
            a.sensor_id,
            a.sensor_val,
            CASE
                WHEN a.sensor_id = 'temperature'THEN
                    CASE
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN
                        CASE
                            WHEN CAST(a.sensor_val AS FLOAT) >= 23.5 AND CAST(a.sensor_val AS FLOAT) < 24.5 THEN 'MINOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 24.5 AND CAST(a.sensor_val AS FLOAT) < 26 THEN 'MAJOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 26 THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    ELSE UPPER(a.sensor_val)
                    END
                WHEN a.sensor_id = 'humidity' THEN
                    CASE
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN
                        CASE
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 40 AND CAST(a.sensor_val AS FLOAT) > 35 ) OR ( CAST(a.sensor_val AS FLOAT) >= 60 AND CAST(a.sensor_val AS FLOAT) < 65 ) THEN 'MINOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 35 AND CAST(a.sensor_val AS FLOAT) > 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 65 AND CAST(a.sensor_val AS FLOAT) < 70 ) THEN 'MAJOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 70 ) THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    END
                ELSE UPPER(a.sensor_val)
            END AS severity
            FROM datacenter_schema.tb_sensor a
            JOIN ( SELECT max(time_id) AS time_id, datacenter_id FROM datacenter_schema.tb_sensor GROUP BY datacenter_id ) b
            ON a.datacenter_id = b.datacenter_id AND a.time_id = b.time_id
            WHERE a.datacenter_id = ?
        ) tempTable RIGHT JOIN (
            SELECT 'CRITICAL' AS severity
            UNION
            SELECT 'MAJOR' AS severity
            UNION
            SELECT 'MINOR' AS severity
            UNION
            SELECT 'NORMAL' AS severity
        ) tempSeverity
        ON tempSeverity.severity = tempTable.severity
        GROUP BY tempSeverity.severity";
        return DB::select(DB::raw($sql),[$datacenter_name]);
    }

    public function getSensorDashboardPercategory($category) {
        $sql = "SELECT * FROM (
            SELECT CONCAT(a.time_id, ' ', a.time_zone) AS time_id,
            a.datacenter_id,
            a.rak_id,
            a.level_id,
            a.sensor_id,
            a.sensor_val,
            CONCAT( UPPER(a.sensor_id), ' ', a.sensor_val ) AS sensor_detail,
            CASE
                WHEN a.sensor_id = 'temperature'THEN
                    CASE 
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                        CASE
                            WHEN CAST(a.sensor_val AS FLOAT) >= 23.5 AND CAST(a.sensor_val AS FLOAT) < 24.5 THEN 'MINOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 24.5 AND CAST(a.sensor_val AS FLOAT) < 26 THEN 'MAJOR'
                            WHEN CAST(a.sensor_val AS FLOAT) >= 26 THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    ELSE UPPER(a.sensor_val)
                    END
                WHEN a.sensor_id = 'humidity' THEN
                    CASE 
                    WHEN a.sensor_val ~ '^[0-9\.]+$' THEN 
                        CASE
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 40 AND CAST(a.sensor_val AS FLOAT) > 35 ) OR ( CAST(a.sensor_val AS FLOAT) >= 60 AND CAST(a.sensor_val AS FLOAT) < 65 ) THEN 'MINOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 35 AND CAST(a.sensor_val AS FLOAT) > 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 65 AND CAST(a.sensor_val AS FLOAT) < 70 ) THEN 'MAJOR'
                            WHEN ( CAST(a.sensor_val AS FLOAT) <= 30 ) OR ( CAST(a.sensor_val AS FLOAT) >= 70 ) THEN 'CRITICAL'
                            ELSE 'NORMAL'
                        END
                    END
                ELSE UPPER(a.sensor_val)
            END AS severity
            FROM datacenter_schema.tb_sensor a
            JOIN ( SELECT max(time_id) AS time_id, datacenter_id FROM datacenter_schema.tb_sensor GROUP BY datacenter_id ) b
            ON a.datacenter_id = b.datacenter_id AND a.time_id = b.time_id
        ) tempTable
        WHERE severity = UPPER(?)
        ORDER BY array_position(array['CRITICAL','MAJOR','MINOR','NORMAL'], severity);";
        return DB::select(DB::raw($sql),[$category]);
    }

    public function getAssetHeader($datacenter_name){
        $sql = "SELECT table_header, table_reference FROM datacenter_schema.tb_reference_detailformat WHERE datacenter = ? ;";
        return DB::select(DB::raw($sql), [$datacenter_name]);
    }

    public function getSensor($datacenter_name,$lantai) {
        $sql = "SELECT a.* FROM datacenter_schema.tb_sensor a
                JOIN (
                    SELECT MAX(time_id) AS time_id, datacenter_id, level_id, rak_id FROM datacenter_schema.tb_sensor
                    GROUP BY datacenter_id, level_id, rak_id
                ) b 
                ON a.time_id = b.time_id AND a.datacenter_id = b.datacenter_id AND a.level_id = b.level_id AND a.rak_id = b.rak_id
                WHERE UPPER(REPLACE(a.datacenter_id,'TTC ', '')) = UPPER(?) AND a.level_id = ? ;";
        return DB::select(DB::raw($sql), [$datacenter_name,$lantai]);
    }

    public function getAssetPerlevel($datacenter_name,$lantai) {
        switch ($datacenter_name) {
            case 'datacenter_tbs':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? ;';
                $param = array( 'TBS', $lantai);
                break;
            case 'datacenter_bsd':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? ;';
                $param = array( 'BSD', $lantai);
                break;
            case 'datacenter_buaran':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? ;';
                $param = array( 'Buaran', $lantai);
                break;
            case 'datacenter_sukoharjo':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset WHERE "ttc" = ? AND "Level" = ? ; ';
                $param = array( 'SUKOHARJO', $lantai);
                break;
            case 'datacenter_gayungan':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_gayungan WHERE "floor" = ? ;';
                $param = array( $lantai);
                break;
            case 'datacenter_arifinahmad':
                if($lantai=="2"){
                    $sql = 'SELECT * FROM datacenter_schema.tb_asset_arifinahmad WHERE "Level" = ? ';
                }
                if($lantai=="3"){
                    $sql = 'SELECT * FROM datacenter_schema.tb_asset_arifinahmad_lt3 WHERE "Floor Coordinate" = ? ';
                }
                if($lantai=="4"){
                    $sql = 'SELECT * datacenter_schema.FROM tb_asset_arifinahmad_lt4 WHERE "Floor Coordinate" = ? ';
                }
                
                $param = array( $lantai);
                break;
            case 'datacenter_sudiang':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_sudiang; ';
                $param = array( );
                break;
        }
        return DB::select(DB::raw($sql), $param);
    }

    public function getAssetRak($datacenter_name,$lantai,$rakid) {
        switch ($datacenter_name) {
            case 'datacenter_tbs':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? AND REPLACE("Zone", \' \', \'\') = ? ';
                $param = array( 'TBS', $lantai, $rakid);
                break;
            case 'datacenter_bsd':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? AND REPLACE("Zone", \' \', \'\') = ? ';
                $param = array( 'BSD', $lantai, $rakid);
                break;
            case 'datacenter_buaran':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_jabotabek WHERE "ttc" = ? AND "Level" = ? AND REPLACE("Zone", \' \', \'\') = ? ';
                $param = array( 'Buaran', $lantai, $rakid);
                break;
            case 'datacenter_sukoharjo':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset WHERE "ttc" = ? AND "Level" = ? AND REPLACE("Zone", \' \', \'\') = UPPER(?) ';
                $param = array( 'SUKOHARJO', $lantai, $rakid);
                break;
            case 'datacenter_gayungan':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_gayungan WHERE "floor" = ? AND "Rack Location" = UPPER(?) ';
                $param = array( $lantai, $rakid);
                break;
            case 'datacenter_arifinahmad':
                if($lantai=="2"){
                    $sql = 'SELECT * FROM datacenter_schema.tb_asset_arifinahmad WHERE "Coordinate" = UPPER(?) ';
                }
                if($lantai=="3"){
                    $sql = 'SELECT * FROM datacenter_schema.tb_asset_arifinahmad_lt3 WHERE "Floor Coordinate" = ? ';
                }
                if($lantai=="4"){
                    $sql = 'SELECT * datacenter_schema.FROM tb_asset_arifinahmad_lt4 WHERE "Floor Coordinate" = ? ';
                }
                
                $param = array( $rakid);
                break;
            case 'datacenter_sudiang':
                $sql = 'SELECT * FROM datacenter_schema.tb_asset_sudiang WHERE UPPER("Cabinet Name") = ? ';
                $number = preg_replace("/[^0-9]/", "", $rakid );
                $number = sprintf("%02d", $number);
                $character = strtoupper( preg_replace("/[^a-zA-Z]/", "", $rakid) );
                $param = array( $character. "" .$number);
                break;
        }

        return DB::select(DB::raw($sql), $param);
    }

    public function getSensorRak($datacenter_name,$level,$rakId) {
        $sql = "SELECT * FROM datacenter_schema.tb_sensor 
        WHERE time_id = ( SELECT MAX(time_id) FROM datacenter_schema.tb_sensor WHERE UPPER(REPLACE(datacenter_id,'TTC ', '')) = UPPER(?) 
        AND level_id = ? AND rak_id = UPPER(?) ) AND UPPER(REPLACE(datacenter_id,'TTC ', '')) = UPPER(?) AND level_id = ? AND rak_id = UPPER(?);";

        $param = array( $datacenter_name, $level, $rakId, $datacenter_name, $level, $rakId);

        return DB::select(DB::raw($sql), $param);
    }
}
