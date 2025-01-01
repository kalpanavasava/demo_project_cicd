<?php
// Load the XML content from the file

// $xmlFilePath = realpath('artifacts-coverage-report/coverage-report.xml');
$xmlFilePath = realpath(__DIR__ . '/../artifacts-coverage-report/coverage-report.xml');
if (!$xmlFilePath || !file_exists($xmlFilePath)) {
    echo "Error: XML file not found.";
    exit;
}

$xmlContent = file_get_contents($xmlFilePath);

$xml = simplexml_load_string($xmlContent);

if (!$xml) {
    echo "Failed to load XML.";
    exit;
}

// Get current date and time for report generation
date_default_timezone_set('Asia/Kolkata'); 
$reportGeneratedAt = date("F j, Y, g:i A");

// Start HTML structure
$html = '<!DOCTYPE html><html lang="en"><head>';
$html .= '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
$html .= '<meta http-equiv="X-UA-Compatible" content="ie=edge"><title>Code Coverage Report</title>';
$html .= '<style>';
$html .= 'body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; margin: 0; padding: 20px; }';
$html .= '.container { max-width: 1200px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }';
$html .= 'h1 { color: #333; font-size: 28px; text-align: center; }';
$html .= 'h2 { color: #555; font-size: 22px; margin-top: 20px; }';
$html .= 'h3 { color: #666; font-size: 20px; margin-top: 15px; }';
$html .= '.file-section, .metrics-section { margin-top: 30px; padding: 15px; border-radius: 6px; background-color: #f7f7f7; }';
$html .= '.file-section { border: 1px solid #ddd; }';
$html .= '.metrics-section table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: auto; }';
$html .= '.metrics-section th, .metrics-section td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }';
$html .= '.metrics-section th { background-color: #e9ecef; }';
$html .= '.covered { background-color: #d4edda; font-weight: bold; color: #155724; }';
$html .= '.uncovered { background-color: #f8d7da; font-weight: bold; color: #721c24; }';
$html .= '.status { font-weight: bold; text-transform: uppercase; }';
$html .= '.line-status { padding: 5px 10px; margin-top: 5px; border-radius: 4px; }';
$html .= '.covered-line { background-color: #d4edda; color: #155724; }';
$html .= '.uncovered-line { background-color: #f8d7da; color: #721c24; }';
$html .= '.summary { background-color: #f1f1f1; padding: 10px; border-radius: 6px; margin-bottom: 20px; }';
$html .= '.summary h3 { margin-bottom: 10px; }';
$html .= '.summary .summary-item { display: flex; justify-content: space-between; margin-bottom: 5px; }';
$html .= '.summary .summary-item span { font-weight: normal; }';
$html .= 'table { width: 100%; border-collapse: collapse; margin-top: 30px; }';
$html .= 'table, th, td { border: 1px solid #ddd; }';
$html .= 'th, td { padding: 8px; text-align: left; }';
$html .= 'th { background-color: #f4f4f4; }';
$html .= '.file-name { font-weight: bold; font-size: 16px; }';
$html .= '.line-status-cell { padding: 4px 8px; }';
$html .= '@media (max-width: 768px) { table { font-size: 12px; overflow-x: auto; display: block; } th, td { padding: 6px 10px; } .summary .summary-item span { font-size: 14px; } .file-section, .metrics-section { padding: 10px; } h1 { font-size: 24px; } }';
$html .= '.table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }';
$html .= '.collapse-btn { padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; text-align: center; font-weight: bold; margin-bottom: 10px; }';
$html .= '.collapse-btn:hover { background-color: #0056b3; }';
$html .= '.collapse-btn:focus { outline: none; }';
$html .= '.collapsed { display: none; }';
$html .= '</style></head><body>';

$html .= '<div class="container"><h1>Code Coverage Report</h1>';
$html .= '<p><strong>Report Generated At:</strong> ' . $reportGeneratedAt . '</p>';  // Display date and time

// Project Summary - Count files dynamically
$totalFiles = 0;
$totalLines = 0;
$totalCoverableLines = 0;
$totalCoveredLines = 0;
$totalUncoveredLines = 0;

// Process files under the package
if (isset($xml->project->package)) {
    foreach ($xml->project->package->file as $file) {
        $totalFiles++;
        $totalLines += (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
        $totalCoverableLines += (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0);
        $totalCoveredLines += (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
        $totalUncoveredLines += (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);
    }
}

// Process files outside of the package
if (isset($xml->project->file)) {
    foreach ($xml->project->file as $file) {
        $totalFiles++;
        $totalLines += (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
        $totalCoverableLines += (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0);
        $totalCoveredLines += (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
        $totalUncoveredLines += (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);
    }
}

// Calculate overall coverage percentage
$overallCoverage = 0;
if ($totalLines > 0) {
    $overallCoverage = ($totalCoveredLines / $totalLines) * 100;
}

$html .= '<div class="summary">';
$html .= '<h3>Project Summary</h3>';
$html .= '<div class="summary-item"><span>Total Files</span><span>' . $totalFiles . '</span></div>';
$html .= '<div class="summary-item"><span>Total Lines of Code (LOC)</span><span>' . $totalLines . '</span></div>';
$html .= '<div class="summary-item"><span>Total Covered Lines</span><span>' . $totalCoveredLines . '</span></div>';
$html .= '<div class="summary-item"><span>Total Uncovered Lines</span><span>' . $totalUncoveredLines . '</span></div>';
$html .= '<div class="summary-item"><span>Overall Project Coverage</span><span>' . number_format($overallCoverage, 2) . '%</span></div>';
$html .= '</div>';

// Files Overview Table
$html .= '<div class="table-container"><table><thead><tr>';
$html .= '<th>File Name</th><th>Total Lines</th><th>Coverable Lines</th><th>Covered Lines</th><th>Uncovered Lines</th><th>Methods</th><th>Statements</th><th>Elements</th><th>Coverage (%)</th><th>Details</th>';
$html .= '</tr></thead><tbody>';

function processFile($file) {
    global $html;

    $fileName = (string) $file['name'];
    $coveredLines = '';
    $uncoveredLines = '';
    $coveredCount = 0;
    $uncoveredCount = 0;

    // Loop through the lines for this file and get covered and uncovered lines
    foreach ($file->line as $line) {
        $lineNum = (int) $line['num'];
        $lineCount = (int) $line['count'];

        if ($lineCount > 0) {
            $coveredLines .= "Line $lineNum<br>";
            $coveredCount++;
        } else {
            $uncoveredLines .= "Line $lineNum<br>";
            $uncoveredCount++;
        }
    }

    // Calculate coverage percentage
    $coveragePercent = 0;
    if ($coveredCount + $uncoveredCount > 0) {
        $coveragePercent = ($coveredCount / ($coveredCount + $uncoveredCount)) * 100;
    }

    // Add file row to the table
    $html .= '<tr>';
    $html .= '<td class="file-name">' . $fileName . '</td>';
    $html .= '<td>' . (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0) . '</td>';
    $html .= '<td>' . (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0) . '</td>';
    $html .= '<td class="covered-line">' . $coveredCount . ' (Covered)</td>';
    $html .= '<td class="uncovered-line">' . $uncoveredCount . ' (Uncovered)</td>';
    $html .= '<td>' . (isset($file->metrics['coveredmethods']) ? (int) $file->metrics['coveredmethods'] : 0) . ' (Covered)</td>';
    $html .= '<td>' . (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0) . ' (Covered)</td>';
    $html .= '<td>' . (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0) . ' (Covered)</td>';
    $html .= '<td>' . number_format($coveragePercent, 2) . '%</td>';
    
    // Add Expand/Collapse button in the table cell
    $html .= '<td class="line-status-cell"><button class="collapse-btn" onclick="toggleCollapse(\'file_' . md5($fileName) . '\')">Expand/Collapse</button></td>';
    
    $html .= '</tr>';

    // File-specific coverage details (expandable below the file summary row)
    $html .= '<tr class="metrics-row"><td colspan="10">';
    $html .= '<div id="file_' . md5($fileName) . '" class="metrics-section collapsed">';

    // Coverage Metrics for this file
    $html .= '<h3>Coverage Metrics</h3>';
    $html .= '<table>';
    $html .= '<tr><th>Metric</th><th>Value</th></tr>';
    $html .= '<tr class="covered"><td>Coverable Lines</td><td>' . (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0) . '</td></tr>';
    $html .= '<tr class="covered"><td>Covered Lines</td><td>' . $coveredCount . '</td></tr>';
    $html .= '<tr class="uncovered"><td>Uncovered Lines</td><td>' . $uncoveredCount . '</td></tr>';
    $html .= '<tr class="covered"><td>Methods</td><td>' . (isset($file->metrics['coveredmethods']) ? (int) $file->metrics['coveredmethods'] : 0) . '</td></tr>';
    $html .= '<tr class="covered"><td>Statements</td><td>' . (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0) . '</td></tr>';
    $html .= '<tr class="covered"><td>Elements</td><td>' . (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0) . '</td></tr>';
    $html .= '<tr class="covered"><td>Coverage (%)</td><td>' . number_format($coveragePercent, 2) . '%</td></tr>';
    $html .= '</table>';

    // Covered and Uncovered Lines
    if ($coveredCount > 0) {
        $html .= '<h3>Covered Lines:</h3>';
        foreach (explode("<br>", $coveredLines) as $line) {
            if (!empty($line)) {
                $html .= '<div class="line-status covered-line"><strong>' . $line . ':</strong> Method - <span>Covered</span></div>';
            }
        }
    }

    if ($uncoveredCount > 0) {
        $html .= '<h3>Uncovered Lines:</h3>';
        foreach (explode("<br>", $uncoveredLines) as $line) {
            if (!empty($line)) {
                $html .= '<div class="line-status uncovered-line"><strong>' . $line . ':</strong> Statement - <span>Uncovered</span></div>';
            }
        }
    }

    $html .= '</div></td></tr>';
}

// Process all files under and outside packages
if (isset($xml->project->package)) {
    foreach ($xml->project->package->file as $file) {
        processFile($file);
    }
}

if (isset($xml->project->file)) {
    foreach ($xml->project->file as $file) {
        processFile($file);
    }
}

$html .= '</tbody></table></div>';
$html .= '<script>';
$html .= 'function toggleCollapse(fileId) {';
$html .= '    var fileSection = document.getElementById(fileId);';
$html .= '    if (fileSection.classList.contains("collapsed")) {';
$html .= '        fileSection.classList.remove("collapsed");';
$html .= '    } else {';
$html .= '        fileSection.classList.add("collapsed");';
$html .= '    }';
$html .= '}</script>';

$html .= '</div></body></html>';

// echo $html;

// Save the HTML report as a file and trigger download
$reportFilePath = 'coverage-report/coverage-report.html';

if (file_exists($reportFilePath)) {
    unlink($reportFilePath); 
}

file_put_contents($reportFilePath, $html);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($reportFilePath) . '"');
header('Content-Length: ' . filesize($reportFilePath));
readfile($reportFilePath);

exit;
?>
