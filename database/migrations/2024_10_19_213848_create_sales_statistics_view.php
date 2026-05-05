<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSalesStatisticsView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW sales_statistics AS
            SELECT
                DATE(i.created_at) AS sale_date,
                SUM(ii.total_price) AS total_sales,
                SUM((ii.sale_price - ii.purchase_price) * ii.quantity) AS total_profit,
                SUM(ii.purchase_price * ii.quantity) AS total_cost
            FROM
                invoices i
            JOIN
                invoice_items ii ON ii.invoice_id = i.id
            GROUP BY
                sale_date
        ");
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS sales_statistics');
    }
}
