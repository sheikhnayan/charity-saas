<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * Excel Export Service (WEBSITE-BASED)
 * Generates formatted Excel files from data arrays
 */
class ExcelExportService
{
    /**
     * Export UTM campaign data to Excel (WEBSITE-BASED)
     */
    public function exportUTMCampaigns($campaigns, $websiteName = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = 'UTM Attribution Analytics';
        if ($websiteName) {
            $title .= ' - ' . $websiteName;
        }
        $sheet->setTitle('UTM Campaigns');
        
        // Add header row with styling
        $headers = [
            'Campaign',
            'Source',
            'Medium',
            'Sessions',
            'Visitors',
            'Conversions',
            'Revenue',
            'Conversion Rate (%)',
            'Avg Revenue/Session',
            'Cost/Conversion'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($campaigns as $campaign) {
            $sheet->setCellValue('A' . $row, $campaign['campaign']);
            $sheet->setCellValue('B' . $row, $campaign['source']);
            $sheet->setCellValue('C' . $row, $campaign['medium']);
            $sheet->setCellValue('D' . $row, $campaign['sessions']);
            $sheet->setCellValue('E' . $row, $campaign['visitors']);
            $sheet->setCellValue('F' . $row, $campaign['conversions']);
            $sheet->setCellValue('G' . $row, $campaign['revenue']);
            $sheet->setCellValue('H' . $row, $campaign['conversion_rate']);
            $sheet->setCellValue('I' . $row, $campaign['avg_revenue_per_session']);
            $sheet->setCellValue('J' . $row, $campaign['cost_per_conversion']);
            
            // Format currency columns
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            
            // Format percentage column
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('0.00');
            
            $row++;
        }
        
        // Add borders to all cells
        $lastRow = $row - 1;
        $sheet->getStyle('A1:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add metadata
        $spreadsheet->getProperties()
            ->setCreator('Charity Platform')
            ->setTitle($title)
            ->setSubject('UTM Campaign Analytics')
            ->setDescription('Website-Based UTM campaign performance report')
            ->setKeywords('utm analytics campaigns website-based');
        
        return $spreadsheet;
    }
    
    /**
     * Export referrer data to Excel (WEBSITE-BASED)
     */
    public function exportReferrers($referrers, $websiteName = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = 'Referrer Analytics';
        if ($websiteName) {
            $title .= ' - ' . $websiteName;
        }
        $sheet->setTitle('Referrers');
        
        // Add header row with styling
        $headers = [
            'Referrer URL',
            'Domain',
            'Sessions',
            'Visitors',
            'Conversions',
            'Revenue',
            'Conversion Rate (%)',
            'Avg Revenue/Session'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($referrers as $referrer) {
            $sheet->setCellValue('A' . $row, $referrer['referrer_url']);
            $sheet->setCellValue('B' . $row, $referrer['domain']);
            $sheet->setCellValue('C' . $row, $referrer['sessions']);
            $sheet->setCellValue('D' . $row, $referrer['visitors']);
            $sheet->setCellValue('E' . $row, $referrer['conversions']);
            $sheet->setCellValue('F' . $row, $referrer['revenue']);
            $sheet->setCellValue('G' . $row, $referrer['conversion_rate']);
            $sheet->setCellValue('H' . $row, $referrer['avg_revenue_per_session']);
            
            // Format currency columns
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            
            // Format percentage column
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('0.00');
            
            $row++;
        }
        
        // Add borders to all cells
        $lastRow = $row - 1;
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add metadata
        $spreadsheet->getProperties()
            ->setCreator('Charity Platform')
            ->setTitle($title)
            ->setSubject('Referrer Analytics')
            ->setDescription('Website-Based referrer performance report')
            ->setKeywords('referrer analytics traffic website-based');
        
        return $spreadsheet;
    }
    
    /**
     * Download Excel file (WEBSITE-BASED)
     */
    public function download(Spreadsheet $spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate and download Excel file directly (WEBSITE-BASED)
     */
    public function generateAndDownload(Spreadsheet $spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ];
        
        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            $headers
        );
    }

    /**
     * Export Analytics Dashboard data to Excel
     */
    public function exportAnalyticsDashboard($stats, $websiteName = null, $startDate, $endDate)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = 'Analytics Dashboard';
        if ($websiteName) {
            $title .= ' - ' . $websiteName;
        }
        $sheet->setTitle('Analytics Dashboard');
        
        // Add title and date range
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        $sheet->setCellValue('A2', 'Date Range: ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Overview Statistics
        $row = 4;
        $sheet->setCellValue('A' . $row, 'Overview Statistics');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ]);
        
        $row++;
        $overviewData = [
            ['Metric', 'Value'],
            ['Page Views', number_format($stats['today']['pageViews'] ?? 0)],
            ['Unique Visitors', number_format($stats['today']['uniqueVisitors'] ?? 0)],
            ['Conversions', number_format($stats['today']['conversions'] ?? 0)],
            ['Revenue', '$' . number_format($stats['today']['revenue'] ?? 0, 2)],
            ['Gross Sales', '$' . number_format($stats['today']['grossSales'] ?? 0, 2)],
            ['Returning Customer Rate', number_format($stats['today']['returningCustomerRate'] ?? 0, 2) . '%'],
            ['Orders Fulfilled', number_format($stats['today']['ordersFulfilled'] ?? 0)]
        ];
        
        $sheet->fromArray($overviewData, null, 'A' . $row);
        
        // Style overview headers
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']]
        ]);
        
        $row += count($overviewData) + 2;
        
        // Weekly Performance - Fixed key from 'weekly' to 'week'
        if (!empty($stats['week'])) {
            $sheet->setCellValue('A' . $row, 'Weekly Performance');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]);
            
            $row++;
            $sheet->fromArray(['Date', 'Page Views', 'Unique Visitors', 'Conversions', 'Revenue'], null, 'A' . $row);
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']]
            ]);
            
            $row++;
            
            // Get data arrays
            $dates = $stats['week']['dates'] ?? [];
            $pageViews = $stats['week']['pageViews'] ?? [];
            $uniqueVisitors = $stats['week']['uniqueVisitors'] ?? [];
            $conversions = $stats['week']['conversions'] ?? [];
            $revenue = $stats['week']['revenue'] ?? [];
            
            // Populate rows
            for ($i = 0; $i < count($dates); $i++) {
                $sheet->setCellValue('A' . $row, $dates[$i] ?? '');
                $sheet->setCellValue('B' . $row, number_format($pageViews[$i] ?? 0));
                $sheet->setCellValue('C' . $row, number_format($uniqueVisitors[$i] ?? 0));
                $sheet->setCellValue('D' . $row, number_format($conversions[$i] ?? 0));
                $sheet->setCellValue('E' . $row, '$' . number_format($revenue[$i] ?? 0, 2));
                $row++;
            }
            
            $row += 2;
        }
        
        // Top Pages
        if (!empty($stats['topPages'])) {
            $sheet->setCellValue('A' . $row, 'Top Pages');
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]);
            
            $row++;
            $sheet->fromArray(['Page', 'Views'], null, 'A' . $row);
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']]
            ]);
            
            $row++;
            foreach ($stats['topPages'] as $page) {
                $sheet->setCellValue('A' . $row, $page['page'] ?? 'Unknown');
                $sheet->setCellValue('B' . $row, number_format($page['views'] ?? 0));
                $row++;
            }
            
            $row += 2;
        }
        
        // Top Referrers
        if (!empty($stats['topReferrers'])) {
            $sheet->setCellValue('A' . $row, 'Top Referrers');
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]);
            
            $row++;
            $sheet->fromArray(['Source', 'Visitors'], null, 'A' . $row);
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']]
            ]);
            
            $row++;
            foreach ($stats['topReferrers'] as $referrer) {
                $sheet->setCellValue('A' . $row, $referrer['source'] ?? 'Unknown');
                $sheet->setCellValue('B' . $row, number_format($referrer['visitors'] ?? 0));
                $row++;
            }
        }
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders
        $sheet->getStyle('A1:E' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        return $spreadsheet;
    }
}
