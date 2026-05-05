<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSalesDetailsView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW sales_details AS
            SELECT
              CAST(i.created_at AS DATE) AS sale_date,
              m.id AS medication_id,
              m.name AS medication_name,
              SUM(ii.quantity) AS quantity,
              m.unit AS unit,
              SUM(ii.sale_price * ii.quantity) AS sale_price,
              SUM(ii.purchase_price * ii.quantity) AS cost_price,
              SUM((ii.sale_price - ii.purchase_price) * ii.quantity) AS profit
            FROM
              invoices i
            JOIN
              invoice_items ii ON ii.invoice_id = i.id
            JOIN
              medications m ON m.id = ii.medication_id
            GROUP BY
              sale_date, medication_id
            ORDER BY
              sale_date DESC;
        ");
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS sales_details');
    }
}
